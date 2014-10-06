<?php

namespace LF14\SysMgmtBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'host_ip' table.
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
class HostIpTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LF14.SysMgmtBundle.Model.map.HostIpTableMap';

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
        $this->setName('host_ip');
        $this->setPhpName('HostIp');
        $this->setClassname('LF14\\SysMgmtBundle\\Model\\HostIp');
        $this->setPackage('src.LF14.SysMgmtBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('host_id', 'HostId', 'INTEGER', 'host', 'id', true, null, null);
        $this->addColumn('ipv4', 'Ipv4', 'VARCHAR', false, 45, null);
        $this->addColumn('ipv6', 'Ipv6', 'VARCHAR', false, 45, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Host', 'LF14\\SysMgmtBundle\\Model\\Host', RelationMap::MANY_TO_ONE, array('host_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // HostIpTableMap
