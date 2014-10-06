<?php

namespace LF14\SysMgmtBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'hosting' table.
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
class HostingTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LF14.SysMgmtBundle.Model.map.HostingTableMap';

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
        $this->setName('hosting');
        $this->setPhpName('Hosting');
        $this->setClassname('LF14\\SysMgmtBundle\\Model\\Hosting');
        $this->setPackage('src.LF14.SysMgmtBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('url', 'Url', 'VARCHAR', true, 45, null);
        $this->addColumn('notes', 'Notes', 'LONGVARCHAR', false, null, null);
        $this->addColumn('date_start', 'DateStart', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('date_end', 'DateEnd', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('domain_id', 'DomainId', 'INTEGER', 'domain', 'id', false, null, null);
        $this->addForeignKey('host_id', 'HostId', 'INTEGER', 'host', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Domain', 'LF14\\SysMgmtBundle\\Model\\Domain', RelationMap::MANY_TO_ONE, array('domain_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('Host', 'LF14\\SysMgmtBundle\\Model\\Host', RelationMap::MANY_TO_ONE, array('host_id' => 'id', ), 'SET NULL', null);
    } // buildRelations()

} // HostingTableMap
