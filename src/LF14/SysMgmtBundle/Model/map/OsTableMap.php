<?php

namespace LF14\SysMgmtBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'os' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.LF14.SysMgmtBundle.Model.map
 */
class OsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LF14.SysMgmtBundle.Model.map.OsTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('os');
        $this->setPhpName('Os');
        $this->setClassname('LF14\\SysMgmtBundle\\Model\\Os');
        $this->setPackage('src.LF14.SysMgmtBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('platform', 'Platform', 'VARCHAR', true, 45, null);
        $this->addColumn('distro', 'Distro', 'VARCHAR', true, 45, null);
        $this->addColumn('version', 'Version', 'VARCHAR', true, 45, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Host', 'LF14\\SysMgmtBundle\\Model\\Host', RelationMap::ONE_TO_MANY, array('id' => 'os_id', ), 'SET NULL', null, 'Hosts');
    } // buildRelations()

} // OsTableMap
