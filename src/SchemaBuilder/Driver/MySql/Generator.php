<?php

namespace Davajlama\SchemaBuilder\Driver\MySql;

/**
 * Description of Generator
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Generator
{
    /** @var \Davajlama\SchemaBuilder\Driver\MySql\Inspector */
    private $inspector;
    
    /** @var Translator */
    private $translator;

    /**
     * @param \Davajlama\SchemaBuilder\Driver\MySql\Inspector $inspector
     */
    public function __construct(\Davajlama\SchemaBuilder\Driver\MySql\Inspector $inspector)
    {
        $this->inspector = $inspector;
    }
    
    public function createTablePatches(\Davajlama\SchemaBuilder\Schema\Table $table)
    {
        $primary = [];
        $columns = [];
        foreach($table->getColumns() as $column) {
            $columns[] = $this->getTranslator()->transColumn($column);
            
            if($column->isPrimary()) {
                $primary[] = $column->getName();
            }
        }
        
        $columns[] = $this->getTranslator()->transPrimaryKey($primary);
        
        foreach($table->getColumns() as $column) {
            if($column->isUnique()) {
                $columns[] = $this->getTranslator()->transUniqueKey($column->getName());
            }
        }
        
        $header = $this->getTranslator()->transCreateTableHeader($table->getName());
        $footer = $this->getTranslator()->transCreateTableFooter($table->getEngine(), $table->getCharset());
        $body   = implode(', ', $columns);
        
        $query = $header . $body . $footer;
        
        $list = new \Davajlama\SchemaBuilder\PatchList();
        $list->createPatch($query, \Davajlama\SchemaBuilder\Patch::NON_BREAKABLE);
        
        return $list;
    }
    
    public function alterTablePatches(\Davajlama\SchemaBuilder\Schema\Table $table)
    {
        $original = [];
        foreach($this->getInspector()->describeTable($table->getName()) as $position => $row) {
            $row['Position'] = $position;
            $original[$row['Field']] = $row;
        }

        $list = new \Davajlama\SchemaBuilder\PatchList();
        $before = null;
        $nonExists = [];
        foreach($table->getColumns() as $column) {
            
            if(array_key_exists($column->getName(), $original)) {
                $origColumn = $original[$column->getName()];
                unset($original[$column->getName()]);
                
                if($patches = $this->compareColumns($origColumn, $column, $table)) {
                    $list->addPatches($patches);
                }
                
            } else {
                $nonExists[] = [
                    'before' => $before,
                    'column' => $column,
                ];
            }
            
            $before = $column;
        }
        
        foreach($nonExists as $non) {
            $pos = ($before = $non['before']) ? "AFTER `{$before->getName()}`" : 'FIRST';
            $col = $non['column'];
            
            $query = "ALTER TABLE `{$table->getName()}` ";
            $query .= "ADD COLUMN " . $this->getTranslator()->transColumn($col);
            $query .= " $pos;";
            
            $list->createPatch($query, \Davajlama\SchemaBuilder\Patch::NON_BREAKABLE);
            
            if($col->isUnique()) {
                $query = "ALTER TABLE `{$table->getName()}` ";
                $query .= "ADD UNIQUE INDEX `{$col->getName()}_UNIQUE` (`{$col->getName()}`)";
                $query .= ";";
                
                $list->createPatch($query, \Davajlama\SchemaBuilder\Patch::NON_BREAKABLE);
            }
        }
        
        foreach($original as $row) {
            $query = "ALTER TABLE `{$table->getName()}` ";
            $query .= "DROP COLUMN `{$row['Field']}`";
            $query .= ";";
            
            $list->createPatch($query, \Davajlama\SchemaBuilder\Patch::BREAKABLE);
        }
        
        return $list;
    }
    
    protected function compareColumns($row, \Davajlama\SchemaBuilder\Schema\Column $column, \Davajlama\SchemaBuilder\Schema\Table $table)
    {
        
        $list = new \Davajlama\SchemaBuilder\PatchList();
        
        $changeUnique   = false;
        $change         = false;
        
        $type = $row['Type'];
        if(strtolower($type) !== strtolower($this->getTranslator()->transType($column->getType()))) {
            $change = true;
        }
        
        $nullable = $row['Null'] === 'YES';
        if($nullable !== $column->isNullable()) {
            $change = true;
        }
        
        $unique = $row['Key'] === 'UNI';
        if($unique !== $column->isUnique()) {
            $changeUnique = true;
        }
        
        $default = $row['Default'];
        if($default !== $column->getDefault()->getValue()) {
            $change = true;
        }
        
        if($change) {
            $query = "ALTER TABLE `{$table->getName()}` ";
            $query .= "CHANGE COLUMN `{$column->getName()}` " . $this->getTranslator()->transColumn($column);
            $query .= ";";
            
            $list->createPatch($query, \Davajlama\SchemaBuilder\Patch::BREAKABLE);
        }
        
        if($changeUnique) {
            
            $query = "ALTER TABLE `{$table->getName()}` ";
            if($column->isUnique()) {
                $query .= "ADD UNIQUE INDEX `{$column->getName()}_UNIQUE` (`{$column->getName()}`)";
            } else {
                $query .= "DROP INDEX `{$column->getName()}_UNIQUE`";
            }
            
            $query .= ";";
            $list->createPatch($query, \Davajlama\SchemaBuilder\Patch::NON_BREAKABLE);
        }
        
        return $list;
    }
    
    /**
     * @return Translator
     */
    protected function getTranslator()
    {
        if($this->translator === null) {
            $this->translator = new Translator();
        }
        
        return $this->translator;
    }
    
    /**
     * @return Inspector
     */
    protected function getInspector()
    {
        return $this->inspector;
    }
    
}