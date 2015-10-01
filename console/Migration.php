<?php
namespace yii2comp\console;

/**
 * Class Migration
 * @package yii2assets\console
 * @author Sebastian Thierer <sebathi@gmail.com>
 *
 * @property string $tableOptions
 */
class Migration extends \yii\db\Migration
{
    /**
     * Tries to do it also and continue if there was an error
     * @var bool
     */
    public $useTryCatch = false;

    /**
     * Shows the exception Message if $useTryCatch is enabled
     * @var bool
     */
    public $showExceptionMessage = false;

    /**
     * Shows the exception stack if $useTryCatch and $showExceptionMessage are enabled
     * @var bool
     */
    public $showStackTrace = false;



    public function getTableOptions()
    {
        if ($this->db->driverName === 'mysql') {
            return 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function createTable($table, $columns, $options = null)
    {
        echo "    > create table $table ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->createTable($table, $columns, $options)->execute();
        } catch (\Exception $e) {
            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }

    /**
     * @inheritdoc
     */
    public function createIndex($name, $table, $columns, $unique = false)
    {
        echo "    > create" . ($unique ? ' unique' : '') . " index $name on $table (" . implode(',', (array)$columns) . ") ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->createIndex($name, $table, $columns, $unique)->execute();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }

    /**
     * @inheritdoc
     */
    public function addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = 'RESTRICT', $update = 'RESTRICT')
    {
        echo "    > add foreign key $name: $table (" . implode(',', (array)$columns) . ") references $refTable (" . implode(',',
                (array)$refColumns) . ") ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update)->execute();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }

    /**
     * @inheritdoc
     */
    public function addColumn($table, $column, $type)
    {
        echo "    > add column $column $type to table $table ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->addColumn($table, $column, $type)->execute();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }


    /**
     * @inheritdoc
     */
    public function dropTable($table)
    {
        echo "    > drop table $table ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->dropTable($table)->execute();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }

    /**
     * @inheritdoc
     */
    public function dropForeignKey($name, $table)
    {
        echo "    > drop foreign key $name from table $table ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->dropForeignKey($name, $table)->execute();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }


    /**
     * @inheritdoc
     */
    public function dropColumn($table, $column)
    {
        echo "    > drop column $column from table $table ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->dropColumn($table, $column)->execute();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }

    /**
     * @param $table
     * @param array $rows
     */
    public function multipleInsert($table, $rows = [])
    {
        echo "    > multiple insert into $table ...";
        $count = 0;
        $time = microtime(true);
        try {
            ob_start();
            foreach ($rows as $columns) {
                $count++;
                $this->insert($table, $columns);
            }
            ob_get_clean();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s) - $count rows.\n";
        }
    }


    /**
     * Creates and executes an INSERT SQL statement.
     * The method will properly escape the column names, and bind the values to be inserted.
     * @param string $table the table that new rows will be inserted into.
     * @param array $columns the column data (name => value) to be inserted into the table.
     */
    public function insert($table, $columns)
    {
        echo "    > insert into $table ...";
        $time = microtime(true);
        try {
            $this->db->createCommand()->insert($table, $columns)->execute();
        } catch (\Exception $e) {

            $this->throwException($e);
        } finally {
            echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
        }
    }


    /**
     * Verifies if the Exception should be thrown or not. Also takes care to
     * show the message and the trace.
     * @param \Exception $e
     */
    public function throwException(\Exception $e)
    {
        if (!$this->useTryCatch) {
            throw $e;
        } elseif ($this->showExceptionMessage) {
            echo $e->getMessage();
            if ($this->showStackTrace){
                echo $e->getTraceAsString();
            }
        }
        echo ' - EXCEPTION - ';
    }
}
