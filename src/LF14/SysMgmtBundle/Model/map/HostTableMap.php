<?php

namespace LF14\SysMgmtBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'host' table.
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
class HostTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LF14.SysMgmtBundle.Model.map.HostTableMap';

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
        $this->setName('host');
        $this->setPhpName('Host');
        $this->setClassname('LF14\\SysMgmtBundle\\Model\\Host');
        $this->setPackage('src.LF14.SysMgmtBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 128, null);
        $this->addColumn('notes', 'Notes', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('client_id', 'ClientId', 'INTEGER', 'client', 'id', false, null, null);
        $this->addForeignKey('location_id', 'LocationId', 'INTEGER', 'location', 'id', false, null, null);
        $this->addForeignKey('host_type_id', 'HostTypeId', 'INTEGER', 'host_type', 'id', false, null, null);
        $this->addForeignKey('host_status_id', 'HostStatusId', 'INTEGER', 'host_status', 'id', false, null, null);
        $this->addForeignKey('os_id', 'OsId', 'INTEGER', 'os', 'id', false, null, null);
        $this->addForeignKey('parent_id', 'ParentId', 'INTEGER', 'host', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Client', 'LF14\\SysMgmtBundle\\Model\\Client', RelationMap::MANY_TO_ONE, array('client_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('HostRelatedByParentId', 'LF14\\SysMgmtBundle\\Model\\Host', RelationMap::MANY_TO_ONE, array('parent_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('HostStatus', 'LF14\\SysMgmtBundle\\Model\\HostStatus', RelationMap::MANY_TO_ONE, array('host_status_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('HostType', 'LF14\\SysMgmtBundle\\Model\\HostType', RelationMap::MANY_TO_ONE, array('host_type_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('Location', 'LF14\\SysMgmtBundle\\Model\\Location', RelationMap::MANY_TO_ONE, array('location_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('Os', 'LF14\\SysMgmtBundle\\Model\\Os', RelationMap::MANY_TO_ONE, array('os_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('HostRelatedById', 'LF14\\SysMgmtBundle\\Model\\Host', RelationMap::ONE_TO_MANY, array('id' => 'parent_id', ), 'SET NULL', 'CASCADE', 'HostsRelatedById');
        $this->addRelation('HostIp', 'LF14\\SysMgmtBundle\\Model\\HostIp', RelationMap::ONE_TO_MANY, array('id' => 'host_id', ), 'CASCADE', null, 'HostIps');
        $this->addRelation('Hosting', 'LF14\\SysMgmtBundle\\Model\\Hosting', RelationMap::ONE_TO_MANY, array('id' => 'host_id', ), 'SET NULL', null, 'Hostings');
        $this->addRelation('Login', 'LF14\\SysMgmtBundle\\Model\\Login', RelationMap::ONE_TO_MANY, array('id' => 'host_id', ), 'CASCADE', null, 'Logins');
    } // buildRelations()

} // HostTableMap
