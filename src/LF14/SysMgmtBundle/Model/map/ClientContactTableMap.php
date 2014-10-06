<?php

namespace LF14\SysMgmtBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'client_contact' table.
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
class ClientContactTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LF14.SysMgmtBundle.Model.map.ClientContactTableMap';

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
        $this->setName('client_contact');
        $this->setPhpName('ClientContact');
        $this->setClassname('LF14\\SysMgmtBundle\\Model\\ClientContact');
        $this->setPackage('src.LF14.SysMgmtBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('client_id', 'ClientId', 'INTEGER', 'client', 'id', true, null, null);
        $this->addForeignKey('contact_id', 'ContactId', 'INTEGER', 'contact', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Client', 'LF14\\SysMgmtBundle\\Model\\Client', RelationMap::MANY_TO_ONE, array('client_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Contact', 'LF14\\SysMgmtBundle\\Model\\Contact', RelationMap::MANY_TO_ONE, array('contact_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // ClientContactTableMap
