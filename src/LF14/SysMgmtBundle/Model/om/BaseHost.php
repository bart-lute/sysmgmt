<?php

namespace LF14\SysMgmtBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use LF14\SysMgmtBundle\Model\Client;
use LF14\SysMgmtBundle\Model\ClientQuery;
use LF14\SysMgmtBundle\Model\Host;
use LF14\SysMgmtBundle\Model\HostIp;
use LF14\SysMgmtBundle\Model\HostIpQuery;
use LF14\SysMgmtBundle\Model\HostPeer;
use LF14\SysMgmtBundle\Model\HostQuery;
use LF14\SysMgmtBundle\Model\HostStatus;
use LF14\SysMgmtBundle\Model\HostStatusQuery;
use LF14\SysMgmtBundle\Model\HostType;
use LF14\SysMgmtBundle\Model\HostTypeQuery;
use LF14\SysMgmtBundle\Model\Hosting;
use LF14\SysMgmtBundle\Model\HostingQuery;
use LF14\SysMgmtBundle\Model\Location;
use LF14\SysMgmtBundle\Model\LocationQuery;
use LF14\SysMgmtBundle\Model\Login;
use LF14\SysMgmtBundle\Model\LoginQuery;
use LF14\SysMgmtBundle\Model\Os;
use LF14\SysMgmtBundle\Model\OsQuery;

abstract class BaseHost extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'LF14\\SysMgmtBundle\\Model\\HostPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        HostPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the notes field.
     * @var        string
     */
    protected $notes;

    /**
     * The value for the client_id field.
     * @var        int
     */
    protected $client_id;

    /**
     * The value for the location_id field.
     * @var        int
     */
    protected $location_id;

    /**
     * The value for the host_type_id field.
     * @var        int
     */
    protected $host_type_id;

    /**
     * The value for the host_status_id field.
     * @var        int
     */
    protected $host_status_id;

    /**
     * The value for the os_id field.
     * @var        int
     */
    protected $os_id;

    /**
     * The value for the parent_id field.
     * @var        int
     */
    protected $parent_id;

    /**
     * The value for the created_at field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        Client
     */
    protected $aClient;

    /**
     * @var        Host
     */
    protected $aHostRelatedByParentId;

    /**
     * @var        HostStatus
     */
    protected $aHostStatus;

    /**
     * @var        HostType
     */
    protected $aHostType;

    /**
     * @var        Location
     */
    protected $aLocation;

    /**
     * @var        Os
     */
    protected $aOs;

    /**
     * @var        PropelObjectCollection|Host[] Collection to store aggregation of Host objects.
     */
    protected $collHostsRelatedById;
    protected $collHostsRelatedByIdPartial;

    /**
     * @var        PropelObjectCollection|HostIp[] Collection to store aggregation of HostIp objects.
     */
    protected $collHostIps;
    protected $collHostIpsPartial;

    /**
     * @var        PropelObjectCollection|Hosting[] Collection to store aggregation of Hosting objects.
     */
    protected $collHostings;
    protected $collHostingsPartial;

    /**
     * @var        PropelObjectCollection|Login[] Collection to store aggregation of Login objects.
     */
    protected $collLogins;
    protected $collLoginsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $hostsRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $hostIpsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $hostingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $loginsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
    }

    /**
     * Initializes internal state of BaseHost object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [notes] column value.
     *
     * @return string
     */
    public function getNotes()
    {

        return $this->notes;
    }

    /**
     * Get the [client_id] column value.
     *
     * @return int
     */
    public function getClientId()
    {

        return $this->client_id;
    }

    /**
     * Get the [location_id] column value.
     *
     * @return int
     */
    public function getLocationId()
    {

        return $this->location_id;
    }

    /**
     * Get the [host_type_id] column value.
     *
     * @return int
     */
    public function getHostTypeId()
    {

        return $this->host_type_id;
    }

    /**
     * Get the [host_status_id] column value.
     *
     * @return int
     */
    public function getHostStatusId()
    {

        return $this->host_status_id;
    }

    /**
     * Get the [os_id] column value.
     *
     * @return int
     */
    public function getOsId()
    {

        return $this->os_id;
    }

    /**
     * Get the [parent_id] column value.
     *
     * @return int
     */
    public function getParentId()
    {

        return $this->parent_id;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = HostPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = HostPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [notes] column.
     *
     * @param  string $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notes !== $v) {
            $this->notes = $v;
            $this->modifiedColumns[] = HostPeer::NOTES;
        }


        return $this;
    } // setNotes()

    /**
     * Set the value of [client_id] column.
     *
     * @param  int $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setClientId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->client_id !== $v) {
            $this->client_id = $v;
            $this->modifiedColumns[] = HostPeer::CLIENT_ID;
        }

        if ($this->aClient !== null && $this->aClient->getId() !== $v) {
            $this->aClient = null;
        }


        return $this;
    } // setClientId()

    /**
     * Set the value of [location_id] column.
     *
     * @param  int $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setLocationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->location_id !== $v) {
            $this->location_id = $v;
            $this->modifiedColumns[] = HostPeer::LOCATION_ID;
        }

        if ($this->aLocation !== null && $this->aLocation->getId() !== $v) {
            $this->aLocation = null;
        }


        return $this;
    } // setLocationId()

    /**
     * Set the value of [host_type_id] column.
     *
     * @param  int $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setHostTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->host_type_id !== $v) {
            $this->host_type_id = $v;
            $this->modifiedColumns[] = HostPeer::HOST_TYPE_ID;
        }

        if ($this->aHostType !== null && $this->aHostType->getId() !== $v) {
            $this->aHostType = null;
        }


        return $this;
    } // setHostTypeId()

    /**
     * Set the value of [host_status_id] column.
     *
     * @param  int $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setHostStatusId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->host_status_id !== $v) {
            $this->host_status_id = $v;
            $this->modifiedColumns[] = HostPeer::HOST_STATUS_ID;
        }

        if ($this->aHostStatus !== null && $this->aHostStatus->getId() !== $v) {
            $this->aHostStatus = null;
        }


        return $this;
    } // setHostStatusId()

    /**
     * Set the value of [os_id] column.
     *
     * @param  int $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setOsId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->os_id !== $v) {
            $this->os_id = $v;
            $this->modifiedColumns[] = HostPeer::OS_ID;
        }

        if ($this->aOs !== null && $this->aOs->getId() !== $v) {
            $this->aOs = null;
        }


        return $this;
    } // setOsId()

    /**
     * Set the value of [parent_id] column.
     *
     * @param  int $v new value
     * @return Host The current object (for fluent API support)
     */
    public function setParentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->parent_id !== $v) {
            $this->parent_id = $v;
            $this->modifiedColumns[] = HostPeer::PARENT_ID;
        }

        if ($this->aHostRelatedByParentId !== null && $this->aHostRelatedByParentId->getId() !== $v) {
            $this->aHostRelatedByParentId = null;
        }


        return $this;
    } // setParentId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Host The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = HostPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Host The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = HostPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->notes = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->client_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->location_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->host_type_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->host_status_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->os_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->parent_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->created_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->updated_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 11; // 11 = HostPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Host object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aClient !== null && $this->client_id !== $this->aClient->getId()) {
            $this->aClient = null;
        }
        if ($this->aLocation !== null && $this->location_id !== $this->aLocation->getId()) {
            $this->aLocation = null;
        }
        if ($this->aHostType !== null && $this->host_type_id !== $this->aHostType->getId()) {
            $this->aHostType = null;
        }
        if ($this->aHostStatus !== null && $this->host_status_id !== $this->aHostStatus->getId()) {
            $this->aHostStatus = null;
        }
        if ($this->aOs !== null && $this->os_id !== $this->aOs->getId()) {
            $this->aOs = null;
        }
        if ($this->aHostRelatedByParentId !== null && $this->parent_id !== $this->aHostRelatedByParentId->getId()) {
            $this->aHostRelatedByParentId = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = HostPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aClient = null;
            $this->aHostRelatedByParentId = null;
            $this->aHostStatus = null;
            $this->aHostType = null;
            $this->aLocation = null;
            $this->aOs = null;
            $this->collHostsRelatedById = null;

            $this->collHostIps = null;

            $this->collHostings = null;

            $this->collLogins = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = HostQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                HostPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aClient !== null) {
                if ($this->aClient->isModified() || $this->aClient->isNew()) {
                    $affectedRows += $this->aClient->save($con);
                }
                $this->setClient($this->aClient);
            }

            if ($this->aHostRelatedByParentId !== null) {
                if ($this->aHostRelatedByParentId->isModified() || $this->aHostRelatedByParentId->isNew()) {
                    $affectedRows += $this->aHostRelatedByParentId->save($con);
                }
                $this->setHostRelatedByParentId($this->aHostRelatedByParentId);
            }

            if ($this->aHostStatus !== null) {
                if ($this->aHostStatus->isModified() || $this->aHostStatus->isNew()) {
                    $affectedRows += $this->aHostStatus->save($con);
                }
                $this->setHostStatus($this->aHostStatus);
            }

            if ($this->aHostType !== null) {
                if ($this->aHostType->isModified() || $this->aHostType->isNew()) {
                    $affectedRows += $this->aHostType->save($con);
                }
                $this->setHostType($this->aHostType);
            }

            if ($this->aLocation !== null) {
                if ($this->aLocation->isModified() || $this->aLocation->isNew()) {
                    $affectedRows += $this->aLocation->save($con);
                }
                $this->setLocation($this->aLocation);
            }

            if ($this->aOs !== null) {
                if ($this->aOs->isModified() || $this->aOs->isNew()) {
                    $affectedRows += $this->aOs->save($con);
                }
                $this->setOs($this->aOs);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->hostsRelatedByIdScheduledForDeletion !== null) {
                if (!$this->hostsRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->hostsRelatedByIdScheduledForDeletion as $hostRelatedById) {
                        // need to save related object because we set the relation to null
                        $hostRelatedById->save($con);
                    }
                    $this->hostsRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collHostsRelatedById !== null) {
                foreach ($this->collHostsRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->hostIpsScheduledForDeletion !== null) {
                if (!$this->hostIpsScheduledForDeletion->isEmpty()) {
                    HostIpQuery::create()
                        ->filterByPrimaryKeys($this->hostIpsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->hostIpsScheduledForDeletion = null;
                }
            }

            if ($this->collHostIps !== null) {
                foreach ($this->collHostIps as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->hostingsScheduledForDeletion !== null) {
                if (!$this->hostingsScheduledForDeletion->isEmpty()) {
                    foreach ($this->hostingsScheduledForDeletion as $hosting) {
                        // need to save related object because we set the relation to null
                        $hosting->save($con);
                    }
                    $this->hostingsScheduledForDeletion = null;
                }
            }

            if ($this->collHostings !== null) {
                foreach ($this->collHostings as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->loginsScheduledForDeletion !== null) {
                if (!$this->loginsScheduledForDeletion->isEmpty()) {
                    LoginQuery::create()
                        ->filterByPrimaryKeys($this->loginsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->loginsScheduledForDeletion = null;
                }
            }

            if ($this->collLogins !== null) {
                foreach ($this->collLogins as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = HostPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . HostPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(HostPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(HostPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(HostPeer::NOTES)) {
            $modifiedColumns[':p' . $index++]  = '`notes`';
        }
        if ($this->isColumnModified(HostPeer::CLIENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`client_id`';
        }
        if ($this->isColumnModified(HostPeer::LOCATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`location_id`';
        }
        if ($this->isColumnModified(HostPeer::HOST_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`host_type_id`';
        }
        if ($this->isColumnModified(HostPeer::HOST_STATUS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`host_status_id`';
        }
        if ($this->isColumnModified(HostPeer::OS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`os_id`';
        }
        if ($this->isColumnModified(HostPeer::PARENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`parent_id`';
        }
        if ($this->isColumnModified(HostPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(HostPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `host` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`notes`':
                        $stmt->bindValue($identifier, $this->notes, PDO::PARAM_STR);
                        break;
                    case '`client_id`':
                        $stmt->bindValue($identifier, $this->client_id, PDO::PARAM_INT);
                        break;
                    case '`location_id`':
                        $stmt->bindValue($identifier, $this->location_id, PDO::PARAM_INT);
                        break;
                    case '`host_type_id`':
                        $stmt->bindValue($identifier, $this->host_type_id, PDO::PARAM_INT);
                        break;
                    case '`host_status_id`':
                        $stmt->bindValue($identifier, $this->host_status_id, PDO::PARAM_INT);
                        break;
                    case '`os_id`':
                        $stmt->bindValue($identifier, $this->os_id, PDO::PARAM_INT);
                        break;
                    case '`parent_id`':
                        $stmt->bindValue($identifier, $this->parent_id, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aClient !== null) {
                if (!$this->aClient->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aClient->getValidationFailures());
                }
            }

            if ($this->aHostRelatedByParentId !== null) {
                if (!$this->aHostRelatedByParentId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aHostRelatedByParentId->getValidationFailures());
                }
            }

            if ($this->aHostStatus !== null) {
                if (!$this->aHostStatus->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aHostStatus->getValidationFailures());
                }
            }

            if ($this->aHostType !== null) {
                if (!$this->aHostType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aHostType->getValidationFailures());
                }
            }

            if ($this->aLocation !== null) {
                if (!$this->aLocation->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLocation->getValidationFailures());
                }
            }

            if ($this->aOs !== null) {
                if (!$this->aOs->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aOs->getValidationFailures());
                }
            }


            if (($retval = HostPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collHostsRelatedById !== null) {
                    foreach ($this->collHostsRelatedById as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collHostIps !== null) {
                    foreach ($this->collHostIps as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collHostings !== null) {
                    foreach ($this->collHostings as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLogins !== null) {
                    foreach ($this->collLogins as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = HostPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getNotes();
                break;
            case 3:
                return $this->getClientId();
                break;
            case 4:
                return $this->getLocationId();
                break;
            case 5:
                return $this->getHostTypeId();
                break;
            case 6:
                return $this->getHostStatusId();
                break;
            case 7:
                return $this->getOsId();
                break;
            case 8:
                return $this->getParentId();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Host'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Host'][$this->getPrimaryKey()] = true;
        $keys = HostPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getNotes(),
            $keys[3] => $this->getClientId(),
            $keys[4] => $this->getLocationId(),
            $keys[5] => $this->getHostTypeId(),
            $keys[6] => $this->getHostStatusId(),
            $keys[7] => $this->getOsId(),
            $keys[8] => $this->getParentId(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aClient) {
                $result['Client'] = $this->aClient->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aHostRelatedByParentId) {
                $result['HostRelatedByParentId'] = $this->aHostRelatedByParentId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aHostStatus) {
                $result['HostStatus'] = $this->aHostStatus->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aHostType) {
                $result['HostType'] = $this->aHostType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLocation) {
                $result['Location'] = $this->aLocation->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aOs) {
                $result['Os'] = $this->aOs->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collHostsRelatedById) {
                $result['HostsRelatedById'] = $this->collHostsRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collHostIps) {
                $result['HostIps'] = $this->collHostIps->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collHostings) {
                $result['Hostings'] = $this->collHostings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLogins) {
                $result['Logins'] = $this->collLogins->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = HostPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setNotes($value);
                break;
            case 3:
                $this->setClientId($value);
                break;
            case 4:
                $this->setLocationId($value);
                break;
            case 5:
                $this->setHostTypeId($value);
                break;
            case 6:
                $this->setHostStatusId($value);
                break;
            case 7:
                $this->setOsId($value);
                break;
            case 8:
                $this->setParentId($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = HostPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setNotes($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setClientId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setLocationId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setHostTypeId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setHostStatusId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setOsId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setParentId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(HostPeer::DATABASE_NAME);

        if ($this->isColumnModified(HostPeer::ID)) $criteria->add(HostPeer::ID, $this->id);
        if ($this->isColumnModified(HostPeer::NAME)) $criteria->add(HostPeer::NAME, $this->name);
        if ($this->isColumnModified(HostPeer::NOTES)) $criteria->add(HostPeer::NOTES, $this->notes);
        if ($this->isColumnModified(HostPeer::CLIENT_ID)) $criteria->add(HostPeer::CLIENT_ID, $this->client_id);
        if ($this->isColumnModified(HostPeer::LOCATION_ID)) $criteria->add(HostPeer::LOCATION_ID, $this->location_id);
        if ($this->isColumnModified(HostPeer::HOST_TYPE_ID)) $criteria->add(HostPeer::HOST_TYPE_ID, $this->host_type_id);
        if ($this->isColumnModified(HostPeer::HOST_STATUS_ID)) $criteria->add(HostPeer::HOST_STATUS_ID, $this->host_status_id);
        if ($this->isColumnModified(HostPeer::OS_ID)) $criteria->add(HostPeer::OS_ID, $this->os_id);
        if ($this->isColumnModified(HostPeer::PARENT_ID)) $criteria->add(HostPeer::PARENT_ID, $this->parent_id);
        if ($this->isColumnModified(HostPeer::CREATED_AT)) $criteria->add(HostPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(HostPeer::UPDATED_AT)) $criteria->add(HostPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(HostPeer::DATABASE_NAME);
        $criteria->add(HostPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Host (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setNotes($this->getNotes());
        $copyObj->setClientId($this->getClientId());
        $copyObj->setLocationId($this->getLocationId());
        $copyObj->setHostTypeId($this->getHostTypeId());
        $copyObj->setHostStatusId($this->getHostStatusId());
        $copyObj->setOsId($this->getOsId());
        $copyObj->setParentId($this->getParentId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getHostsRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addHostRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getHostIps() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addHostIp($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getHostings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addHosting($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLogins() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLogin($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Host Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return HostPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new HostPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Client object.
     *
     * @param                  Client $v
     * @return Host The current object (for fluent API support)
     * @throws PropelException
     */
    public function setClient(Client $v = null)
    {
        if ($v === null) {
            $this->setClientId(NULL);
        } else {
            $this->setClientId($v->getId());
        }

        $this->aClient = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Client object, it will not be re-added.
        if ($v !== null) {
            $v->addHost($this);
        }


        return $this;
    }


    /**
     * Get the associated Client object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Client The associated Client object.
     * @throws PropelException
     */
    public function getClient(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aClient === null && ($this->client_id !== null) && $doQuery) {
            $this->aClient = ClientQuery::create()->findPk($this->client_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aClient->addHosts($this);
             */
        }

        return $this->aClient;
    }

    /**
     * Declares an association between this object and a Host object.
     *
     * @param                  Host $v
     * @return Host The current object (for fluent API support)
     * @throws PropelException
     */
    public function setHostRelatedByParentId(Host $v = null)
    {
        if ($v === null) {
            $this->setParentId(NULL);
        } else {
            $this->setParentId($v->getId());
        }

        $this->aHostRelatedByParentId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Host object, it will not be re-added.
        if ($v !== null) {
            $v->addHostRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated Host object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Host The associated Host object.
     * @throws PropelException
     */
    public function getHostRelatedByParentId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aHostRelatedByParentId === null && ($this->parent_id !== null) && $doQuery) {
            $this->aHostRelatedByParentId = HostQuery::create()->findPk($this->parent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aHostRelatedByParentId->addHostsRelatedById($this);
             */
        }

        return $this->aHostRelatedByParentId;
    }

    /**
     * Declares an association between this object and a HostStatus object.
     *
     * @param                  HostStatus $v
     * @return Host The current object (for fluent API support)
     * @throws PropelException
     */
    public function setHostStatus(HostStatus $v = null)
    {
        if ($v === null) {
            $this->setHostStatusId(NULL);
        } else {
            $this->setHostStatusId($v->getId());
        }

        $this->aHostStatus = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the HostStatus object, it will not be re-added.
        if ($v !== null) {
            $v->addHost($this);
        }


        return $this;
    }


    /**
     * Get the associated HostStatus object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return HostStatus The associated HostStatus object.
     * @throws PropelException
     */
    public function getHostStatus(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aHostStatus === null && ($this->host_status_id !== null) && $doQuery) {
            $this->aHostStatus = HostStatusQuery::create()->findPk($this->host_status_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aHostStatus->addHosts($this);
             */
        }

        return $this->aHostStatus;
    }

    /**
     * Declares an association between this object and a HostType object.
     *
     * @param                  HostType $v
     * @return Host The current object (for fluent API support)
     * @throws PropelException
     */
    public function setHostType(HostType $v = null)
    {
        if ($v === null) {
            $this->setHostTypeId(NULL);
        } else {
            $this->setHostTypeId($v->getId());
        }

        $this->aHostType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the HostType object, it will not be re-added.
        if ($v !== null) {
            $v->addHost($this);
        }


        return $this;
    }


    /**
     * Get the associated HostType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return HostType The associated HostType object.
     * @throws PropelException
     */
    public function getHostType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aHostType === null && ($this->host_type_id !== null) && $doQuery) {
            $this->aHostType = HostTypeQuery::create()->findPk($this->host_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aHostType->addHosts($this);
             */
        }

        return $this->aHostType;
    }

    /**
     * Declares an association between this object and a Location object.
     *
     * @param                  Location $v
     * @return Host The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLocation(Location $v = null)
    {
        if ($v === null) {
            $this->setLocationId(NULL);
        } else {
            $this->setLocationId($v->getId());
        }

        $this->aLocation = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Location object, it will not be re-added.
        if ($v !== null) {
            $v->addHost($this);
        }


        return $this;
    }


    /**
     * Get the associated Location object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Location The associated Location object.
     * @throws PropelException
     */
    public function getLocation(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aLocation === null && ($this->location_id !== null) && $doQuery) {
            $this->aLocation = LocationQuery::create()->findPk($this->location_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLocation->addHosts($this);
             */
        }

        return $this->aLocation;
    }

    /**
     * Declares an association between this object and a Os object.
     *
     * @param                  Os $v
     * @return Host The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOs(Os $v = null)
    {
        if ($v === null) {
            $this->setOsId(NULL);
        } else {
            $this->setOsId($v->getId());
        }

        $this->aOs = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Os object, it will not be re-added.
        if ($v !== null) {
            $v->addHost($this);
        }


        return $this;
    }


    /**
     * Get the associated Os object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Os The associated Os object.
     * @throws PropelException
     */
    public function getOs(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aOs === null && ($this->os_id !== null) && $doQuery) {
            $this->aOs = OsQuery::create()->findPk($this->os_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOs->addHosts($this);
             */
        }

        return $this->aOs;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('HostRelatedById' == $relationName) {
            $this->initHostsRelatedById();
        }
        if ('HostIp' == $relationName) {
            $this->initHostIps();
        }
        if ('Hosting' == $relationName) {
            $this->initHostings();
        }
        if ('Login' == $relationName) {
            $this->initLogins();
        }
    }

    /**
     * Clears out the collHostsRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Host The current object (for fluent API support)
     * @see        addHostsRelatedById()
     */
    public function clearHostsRelatedById()
    {
        $this->collHostsRelatedById = null; // important to set this to null since that means it is uninitialized
        $this->collHostsRelatedByIdPartial = null;

        return $this;
    }

    /**
     * reset is the collHostsRelatedById collection loaded partially
     *
     * @return void
     */
    public function resetPartialHostsRelatedById($v = true)
    {
        $this->collHostsRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collHostsRelatedById collection.
     *
     * By default this just sets the collHostsRelatedById collection to an empty array (like clearcollHostsRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initHostsRelatedById($overrideExisting = true)
    {
        if (null !== $this->collHostsRelatedById && !$overrideExisting) {
            return;
        }
        $this->collHostsRelatedById = new PropelObjectCollection();
        $this->collHostsRelatedById->setModel('Host');
    }

    /**
     * Gets an array of Host objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Host is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Host[] List of Host objects
     * @throws PropelException
     */
    public function getHostsRelatedById($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collHostsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collHostsRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collHostsRelatedById) {
                // return empty collection
                $this->initHostsRelatedById();
            } else {
                $collHostsRelatedById = HostQuery::create(null, $criteria)
                    ->filterByHostRelatedByParentId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collHostsRelatedByIdPartial && count($collHostsRelatedById)) {
                      $this->initHostsRelatedById(false);

                      foreach ($collHostsRelatedById as $obj) {
                        if (false == $this->collHostsRelatedById->contains($obj)) {
                          $this->collHostsRelatedById->append($obj);
                        }
                      }

                      $this->collHostsRelatedByIdPartial = true;
                    }

                    $collHostsRelatedById->getInternalIterator()->rewind();

                    return $collHostsRelatedById;
                }

                if ($partial && $this->collHostsRelatedById) {
                    foreach ($this->collHostsRelatedById as $obj) {
                        if ($obj->isNew()) {
                            $collHostsRelatedById[] = $obj;
                        }
                    }
                }

                $this->collHostsRelatedById = $collHostsRelatedById;
                $this->collHostsRelatedByIdPartial = false;
            }
        }

        return $this->collHostsRelatedById;
    }

    /**
     * Sets a collection of HostRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $hostsRelatedById A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Host The current object (for fluent API support)
     */
    public function setHostsRelatedById(PropelCollection $hostsRelatedById, PropelPDO $con = null)
    {
        $hostsRelatedByIdToDelete = $this->getHostsRelatedById(new Criteria(), $con)->diff($hostsRelatedById);


        $this->hostsRelatedByIdScheduledForDeletion = $hostsRelatedByIdToDelete;

        foreach ($hostsRelatedByIdToDelete as $hostRelatedByIdRemoved) {
            $hostRelatedByIdRemoved->setHostRelatedByParentId(null);
        }

        $this->collHostsRelatedById = null;
        foreach ($hostsRelatedById as $hostRelatedById) {
            $this->addHostRelatedById($hostRelatedById);
        }

        $this->collHostsRelatedById = $hostsRelatedById;
        $this->collHostsRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Host objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Host objects.
     * @throws PropelException
     */
    public function countHostsRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collHostsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collHostsRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collHostsRelatedById) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getHostsRelatedById());
            }
            $query = HostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByHostRelatedByParentId($this)
                ->count($con);
        }

        return count($this->collHostsRelatedById);
    }

    /**
     * Method called to associate a Host object to this object
     * through the Host foreign key attribute.
     *
     * @param    Host $l Host
     * @return Host The current object (for fluent API support)
     */
    public function addHostRelatedById(Host $l)
    {
        if ($this->collHostsRelatedById === null) {
            $this->initHostsRelatedById();
            $this->collHostsRelatedByIdPartial = true;
        }

        if (!in_array($l, $this->collHostsRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddHostRelatedById($l);

            if ($this->hostsRelatedByIdScheduledForDeletion and $this->hostsRelatedByIdScheduledForDeletion->contains($l)) {
                $this->hostsRelatedByIdScheduledForDeletion->remove($this->hostsRelatedByIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	HostRelatedById $hostRelatedById The hostRelatedById object to add.
     */
    protected function doAddHostRelatedById($hostRelatedById)
    {
        $this->collHostsRelatedById[]= $hostRelatedById;
        $hostRelatedById->setHostRelatedByParentId($this);
    }

    /**
     * @param	HostRelatedById $hostRelatedById The hostRelatedById object to remove.
     * @return Host The current object (for fluent API support)
     */
    public function removeHostRelatedById($hostRelatedById)
    {
        if ($this->getHostsRelatedById()->contains($hostRelatedById)) {
            $this->collHostsRelatedById->remove($this->collHostsRelatedById->search($hostRelatedById));
            if (null === $this->hostsRelatedByIdScheduledForDeletion) {
                $this->hostsRelatedByIdScheduledForDeletion = clone $this->collHostsRelatedById;
                $this->hostsRelatedByIdScheduledForDeletion->clear();
            }
            $this->hostsRelatedByIdScheduledForDeletion[]= $hostRelatedById;
            $hostRelatedById->setHostRelatedByParentId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Host is new, it will return
     * an empty collection; or if this Host has previously
     * been saved, it will retrieve related HostsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Host.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsRelatedByIdJoinClient($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('Client', $join_behavior);

        return $this->getHostsRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Host is new, it will return
     * an empty collection; or if this Host has previously
     * been saved, it will retrieve related HostsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Host.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsRelatedByIdJoinHostStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('HostStatus', $join_behavior);

        return $this->getHostsRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Host is new, it will return
     * an empty collection; or if this Host has previously
     * been saved, it will retrieve related HostsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Host.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsRelatedByIdJoinHostType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('HostType', $join_behavior);

        return $this->getHostsRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Host is new, it will return
     * an empty collection; or if this Host has previously
     * been saved, it will retrieve related HostsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Host.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsRelatedByIdJoinLocation($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('Location', $join_behavior);

        return $this->getHostsRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Host is new, it will return
     * an empty collection; or if this Host has previously
     * been saved, it will retrieve related HostsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Host.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsRelatedByIdJoinOs($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('Os', $join_behavior);

        return $this->getHostsRelatedById($query, $con);
    }

    /**
     * Clears out the collHostIps collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Host The current object (for fluent API support)
     * @see        addHostIps()
     */
    public function clearHostIps()
    {
        $this->collHostIps = null; // important to set this to null since that means it is uninitialized
        $this->collHostIpsPartial = null;

        return $this;
    }

    /**
     * reset is the collHostIps collection loaded partially
     *
     * @return void
     */
    public function resetPartialHostIps($v = true)
    {
        $this->collHostIpsPartial = $v;
    }

    /**
     * Initializes the collHostIps collection.
     *
     * By default this just sets the collHostIps collection to an empty array (like clearcollHostIps());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initHostIps($overrideExisting = true)
    {
        if (null !== $this->collHostIps && !$overrideExisting) {
            return;
        }
        $this->collHostIps = new PropelObjectCollection();
        $this->collHostIps->setModel('HostIp');
    }

    /**
     * Gets an array of HostIp objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Host is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|HostIp[] List of HostIp objects
     * @throws PropelException
     */
    public function getHostIps($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collHostIpsPartial && !$this->isNew();
        if (null === $this->collHostIps || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collHostIps) {
                // return empty collection
                $this->initHostIps();
            } else {
                $collHostIps = HostIpQuery::create(null, $criteria)
                    ->filterByHost($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collHostIpsPartial && count($collHostIps)) {
                      $this->initHostIps(false);

                      foreach ($collHostIps as $obj) {
                        if (false == $this->collHostIps->contains($obj)) {
                          $this->collHostIps->append($obj);
                        }
                      }

                      $this->collHostIpsPartial = true;
                    }

                    $collHostIps->getInternalIterator()->rewind();

                    return $collHostIps;
                }

                if ($partial && $this->collHostIps) {
                    foreach ($this->collHostIps as $obj) {
                        if ($obj->isNew()) {
                            $collHostIps[] = $obj;
                        }
                    }
                }

                $this->collHostIps = $collHostIps;
                $this->collHostIpsPartial = false;
            }
        }

        return $this->collHostIps;
    }

    /**
     * Sets a collection of HostIp objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $hostIps A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Host The current object (for fluent API support)
     */
    public function setHostIps(PropelCollection $hostIps, PropelPDO $con = null)
    {
        $hostIpsToDelete = $this->getHostIps(new Criteria(), $con)->diff($hostIps);


        $this->hostIpsScheduledForDeletion = $hostIpsToDelete;

        foreach ($hostIpsToDelete as $hostIpRemoved) {
            $hostIpRemoved->setHost(null);
        }

        $this->collHostIps = null;
        foreach ($hostIps as $hostIp) {
            $this->addHostIp($hostIp);
        }

        $this->collHostIps = $hostIps;
        $this->collHostIpsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related HostIp objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related HostIp objects.
     * @throws PropelException
     */
    public function countHostIps(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collHostIpsPartial && !$this->isNew();
        if (null === $this->collHostIps || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collHostIps) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getHostIps());
            }
            $query = HostIpQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByHost($this)
                ->count($con);
        }

        return count($this->collHostIps);
    }

    /**
     * Method called to associate a HostIp object to this object
     * through the HostIp foreign key attribute.
     *
     * @param    HostIp $l HostIp
     * @return Host The current object (for fluent API support)
     */
    public function addHostIp(HostIp $l)
    {
        if ($this->collHostIps === null) {
            $this->initHostIps();
            $this->collHostIpsPartial = true;
        }

        if (!in_array($l, $this->collHostIps->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddHostIp($l);

            if ($this->hostIpsScheduledForDeletion and $this->hostIpsScheduledForDeletion->contains($l)) {
                $this->hostIpsScheduledForDeletion->remove($this->hostIpsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	HostIp $hostIp The hostIp object to add.
     */
    protected function doAddHostIp($hostIp)
    {
        $this->collHostIps[]= $hostIp;
        $hostIp->setHost($this);
    }

    /**
     * @param	HostIp $hostIp The hostIp object to remove.
     * @return Host The current object (for fluent API support)
     */
    public function removeHostIp($hostIp)
    {
        if ($this->getHostIps()->contains($hostIp)) {
            $this->collHostIps->remove($this->collHostIps->search($hostIp));
            if (null === $this->hostIpsScheduledForDeletion) {
                $this->hostIpsScheduledForDeletion = clone $this->collHostIps;
                $this->hostIpsScheduledForDeletion->clear();
            }
            $this->hostIpsScheduledForDeletion[]= clone $hostIp;
            $hostIp->setHost(null);
        }

        return $this;
    }

    /**
     * Clears out the collHostings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Host The current object (for fluent API support)
     * @see        addHostings()
     */
    public function clearHostings()
    {
        $this->collHostings = null; // important to set this to null since that means it is uninitialized
        $this->collHostingsPartial = null;

        return $this;
    }

    /**
     * reset is the collHostings collection loaded partially
     *
     * @return void
     */
    public function resetPartialHostings($v = true)
    {
        $this->collHostingsPartial = $v;
    }

    /**
     * Initializes the collHostings collection.
     *
     * By default this just sets the collHostings collection to an empty array (like clearcollHostings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initHostings($overrideExisting = true)
    {
        if (null !== $this->collHostings && !$overrideExisting) {
            return;
        }
        $this->collHostings = new PropelObjectCollection();
        $this->collHostings->setModel('Hosting');
    }

    /**
     * Gets an array of Hosting objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Host is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Hosting[] List of Hosting objects
     * @throws PropelException
     */
    public function getHostings($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collHostingsPartial && !$this->isNew();
        if (null === $this->collHostings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collHostings) {
                // return empty collection
                $this->initHostings();
            } else {
                $collHostings = HostingQuery::create(null, $criteria)
                    ->filterByHost($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collHostingsPartial && count($collHostings)) {
                      $this->initHostings(false);

                      foreach ($collHostings as $obj) {
                        if (false == $this->collHostings->contains($obj)) {
                          $this->collHostings->append($obj);
                        }
                      }

                      $this->collHostingsPartial = true;
                    }

                    $collHostings->getInternalIterator()->rewind();

                    return $collHostings;
                }

                if ($partial && $this->collHostings) {
                    foreach ($this->collHostings as $obj) {
                        if ($obj->isNew()) {
                            $collHostings[] = $obj;
                        }
                    }
                }

                $this->collHostings = $collHostings;
                $this->collHostingsPartial = false;
            }
        }

        return $this->collHostings;
    }

    /**
     * Sets a collection of Hosting objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $hostings A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Host The current object (for fluent API support)
     */
    public function setHostings(PropelCollection $hostings, PropelPDO $con = null)
    {
        $hostingsToDelete = $this->getHostings(new Criteria(), $con)->diff($hostings);


        $this->hostingsScheduledForDeletion = $hostingsToDelete;

        foreach ($hostingsToDelete as $hostingRemoved) {
            $hostingRemoved->setHost(null);
        }

        $this->collHostings = null;
        foreach ($hostings as $hosting) {
            $this->addHosting($hosting);
        }

        $this->collHostings = $hostings;
        $this->collHostingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Hosting objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Hosting objects.
     * @throws PropelException
     */
    public function countHostings(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collHostingsPartial && !$this->isNew();
        if (null === $this->collHostings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collHostings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getHostings());
            }
            $query = HostingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByHost($this)
                ->count($con);
        }

        return count($this->collHostings);
    }

    /**
     * Method called to associate a Hosting object to this object
     * through the Hosting foreign key attribute.
     *
     * @param    Hosting $l Hosting
     * @return Host The current object (for fluent API support)
     */
    public function addHosting(Hosting $l)
    {
        if ($this->collHostings === null) {
            $this->initHostings();
            $this->collHostingsPartial = true;
        }

        if (!in_array($l, $this->collHostings->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddHosting($l);

            if ($this->hostingsScheduledForDeletion and $this->hostingsScheduledForDeletion->contains($l)) {
                $this->hostingsScheduledForDeletion->remove($this->hostingsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Hosting $hosting The hosting object to add.
     */
    protected function doAddHosting($hosting)
    {
        $this->collHostings[]= $hosting;
        $hosting->setHost($this);
    }

    /**
     * @param	Hosting $hosting The hosting object to remove.
     * @return Host The current object (for fluent API support)
     */
    public function removeHosting($hosting)
    {
        if ($this->getHostings()->contains($hosting)) {
            $this->collHostings->remove($this->collHostings->search($hosting));
            if (null === $this->hostingsScheduledForDeletion) {
                $this->hostingsScheduledForDeletion = clone $this->collHostings;
                $this->hostingsScheduledForDeletion->clear();
            }
            $this->hostingsScheduledForDeletion[]= $hosting;
            $hosting->setHost(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Host is new, it will return
     * an empty collection; or if this Host has previously
     * been saved, it will retrieve related Hostings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Host.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Hosting[] List of Hosting objects
     */
    public function getHostingsJoinDomain($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostingQuery::create(null, $criteria);
        $query->joinWith('Domain', $join_behavior);

        return $this->getHostings($query, $con);
    }

    /**
     * Clears out the collLogins collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Host The current object (for fluent API support)
     * @see        addLogins()
     */
    public function clearLogins()
    {
        $this->collLogins = null; // important to set this to null since that means it is uninitialized
        $this->collLoginsPartial = null;

        return $this;
    }

    /**
     * reset is the collLogins collection loaded partially
     *
     * @return void
     */
    public function resetPartialLogins($v = true)
    {
        $this->collLoginsPartial = $v;
    }

    /**
     * Initializes the collLogins collection.
     *
     * By default this just sets the collLogins collection to an empty array (like clearcollLogins());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLogins($overrideExisting = true)
    {
        if (null !== $this->collLogins && !$overrideExisting) {
            return;
        }
        $this->collLogins = new PropelObjectCollection();
        $this->collLogins->setModel('Login');
    }

    /**
     * Gets an array of Login objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Host is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Login[] List of Login objects
     * @throws PropelException
     */
    public function getLogins($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLoginsPartial && !$this->isNew();
        if (null === $this->collLogins || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLogins) {
                // return empty collection
                $this->initLogins();
            } else {
                $collLogins = LoginQuery::create(null, $criteria)
                    ->filterByHost($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLoginsPartial && count($collLogins)) {
                      $this->initLogins(false);

                      foreach ($collLogins as $obj) {
                        if (false == $this->collLogins->contains($obj)) {
                          $this->collLogins->append($obj);
                        }
                      }

                      $this->collLoginsPartial = true;
                    }

                    $collLogins->getInternalIterator()->rewind();

                    return $collLogins;
                }

                if ($partial && $this->collLogins) {
                    foreach ($this->collLogins as $obj) {
                        if ($obj->isNew()) {
                            $collLogins[] = $obj;
                        }
                    }
                }

                $this->collLogins = $collLogins;
                $this->collLoginsPartial = false;
            }
        }

        return $this->collLogins;
    }

    /**
     * Sets a collection of Login objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $logins A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Host The current object (for fluent API support)
     */
    public function setLogins(PropelCollection $logins, PropelPDO $con = null)
    {
        $loginsToDelete = $this->getLogins(new Criteria(), $con)->diff($logins);


        $this->loginsScheduledForDeletion = $loginsToDelete;

        foreach ($loginsToDelete as $loginRemoved) {
            $loginRemoved->setHost(null);
        }

        $this->collLogins = null;
        foreach ($logins as $login) {
            $this->addLogin($login);
        }

        $this->collLogins = $logins;
        $this->collLoginsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Login objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Login objects.
     * @throws PropelException
     */
    public function countLogins(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLoginsPartial && !$this->isNew();
        if (null === $this->collLogins || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLogins) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLogins());
            }
            $query = LoginQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByHost($this)
                ->count($con);
        }

        return count($this->collLogins);
    }

    /**
     * Method called to associate a Login object to this object
     * through the Login foreign key attribute.
     *
     * @param    Login $l Login
     * @return Host The current object (for fluent API support)
     */
    public function addLogin(Login $l)
    {
        if ($this->collLogins === null) {
            $this->initLogins();
            $this->collLoginsPartial = true;
        }

        if (!in_array($l, $this->collLogins->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLogin($l);

            if ($this->loginsScheduledForDeletion and $this->loginsScheduledForDeletion->contains($l)) {
                $this->loginsScheduledForDeletion->remove($this->loginsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Login $login The login object to add.
     */
    protected function doAddLogin($login)
    {
        $this->collLogins[]= $login;
        $login->setHost($this);
    }

    /**
     * @param	Login $login The login object to remove.
     * @return Host The current object (for fluent API support)
     */
    public function removeLogin($login)
    {
        if ($this->getLogins()->contains($login)) {
            $this->collLogins->remove($this->collLogins->search($login));
            if (null === $this->loginsScheduledForDeletion) {
                $this->loginsScheduledForDeletion = clone $this->collLogins;
                $this->loginsScheduledForDeletion->clear();
            }
            $this->loginsScheduledForDeletion[]= clone $login;
            $login->setHost(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Host is new, it will return
     * an empty collection; or if this Host has previously
     * been saved, it will retrieve related Logins from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Host.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Login[] List of Login objects
     */
    public function getLoginsJoinLoginType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LoginQuery::create(null, $criteria);
        $query->joinWith('LoginType', $join_behavior);

        return $this->getLogins($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->notes = null;
        $this->client_id = null;
        $this->location_id = null;
        $this->host_type_id = null;
        $this->host_status_id = null;
        $this->os_id = null;
        $this->parent_id = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collHostsRelatedById) {
                foreach ($this->collHostsRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collHostIps) {
                foreach ($this->collHostIps as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collHostings) {
                foreach ($this->collHostings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLogins) {
                foreach ($this->collLogins as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aClient instanceof Persistent) {
              $this->aClient->clearAllReferences($deep);
            }
            if ($this->aHostRelatedByParentId instanceof Persistent) {
              $this->aHostRelatedByParentId->clearAllReferences($deep);
            }
            if ($this->aHostStatus instanceof Persistent) {
              $this->aHostStatus->clearAllReferences($deep);
            }
            if ($this->aHostType instanceof Persistent) {
              $this->aHostType->clearAllReferences($deep);
            }
            if ($this->aLocation instanceof Persistent) {
              $this->aLocation->clearAllReferences($deep);
            }
            if ($this->aOs instanceof Persistent) {
              $this->aOs->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collHostsRelatedById instanceof PropelCollection) {
            $this->collHostsRelatedById->clearIterator();
        }
        $this->collHostsRelatedById = null;
        if ($this->collHostIps instanceof PropelCollection) {
            $this->collHostIps->clearIterator();
        }
        $this->collHostIps = null;
        if ($this->collHostings instanceof PropelCollection) {
            $this->collHostings->clearIterator();
        }
        $this->collHostings = null;
        if ($this->collLogins instanceof PropelCollection) {
            $this->collLogins->clearIterator();
        }
        $this->collLogins = null;
        $this->aClient = null;
        $this->aHostRelatedByParentId = null;
        $this->aHostStatus = null;
        $this->aHostType = null;
        $this->aLocation = null;
        $this->aOs = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(HostPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
