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
use LF14\SysMgmtBundle\Model\Domain;
use LF14\SysMgmtBundle\Model\DomainPeer;
use LF14\SysMgmtBundle\Model\DomainQuery;
use LF14\SysMgmtBundle\Model\Hosting;
use LF14\SysMgmtBundle\Model\HostingQuery;
use LF14\SysMgmtBundle\Model\Mailbox;
use LF14\SysMgmtBundle\Model\MailboxQuery;
use LF14\SysMgmtBundle\Model\Nameserver;
use LF14\SysMgmtBundle\Model\NameserverQuery;

abstract class BaseDomain extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'LF14\\SysMgmtBundle\\Model\\DomainPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DomainPeer
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
     * The value for the date_start field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        string
     */
    protected $date_start;

    /**
     * The value for the date_end field.
     * @var        string
     */
    protected $date_end;

    /**
     * The value for the client_id field.
     * @var        int
     */
    protected $client_id;

    /**
     * The value for the nameserver_id field.
     * @var        int
     */
    protected $nameserver_id;

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
     * @var        Nameserver
     */
    protected $aNameserver;

    /**
     * @var        PropelObjectCollection|Hosting[] Collection to store aggregation of Hosting objects.
     */
    protected $collHostings;
    protected $collHostingsPartial;

    /**
     * @var        PropelObjectCollection|Mailbox[] Collection to store aggregation of Mailbox objects.
     */
    protected $collMailboxen;
    protected $collMailboxenPartial;

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
    protected $hostingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $mailboxenScheduledForDeletion = null;

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
     * Initializes internal state of BaseDomain object.
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
     * Get the [optionally formatted] temporal [date_start] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateStart($format = null)
    {
        if ($this->date_start === null) {
            return null;
        }

        if ($this->date_start === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->date_start);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_start, true), $x);
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
     * Get the [optionally formatted] temporal [date_end] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateEnd($format = null)
    {
        if ($this->date_end === null) {
            return null;
        }

        if ($this->date_end === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->date_end);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_end, true), $x);
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
     * Get the [client_id] column value.
     *
     * @return int
     */
    public function getClientId()
    {

        return $this->client_id;
    }

    /**
     * Get the [nameserver_id] column value.
     *
     * @return int
     */
    public function getNameserverId()
    {

        return $this->nameserver_id;
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
     * @return Domain The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DomainPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Domain The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = DomainPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [notes] column.
     *
     * @param  string $v new value
     * @return Domain The current object (for fluent API support)
     */
    public function setNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notes !== $v) {
            $this->notes = $v;
            $this->modifiedColumns[] = DomainPeer::NOTES;
        }


        return $this;
    } // setNotes()

    /**
     * Sets the value of [date_start] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Domain The current object (for fluent API support)
     */
    public function setDateStart($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_start !== null || $dt !== null) {
            $currentDateAsString = ($this->date_start !== null && $tmpDt = new DateTime($this->date_start)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_start = $newDateAsString;
                $this->modifiedColumns[] = DomainPeer::DATE_START;
            }
        } // if either are not null


        return $this;
    } // setDateStart()

    /**
     * Sets the value of [date_end] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Domain The current object (for fluent API support)
     */
    public function setDateEnd($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_end !== null || $dt !== null) {
            $currentDateAsString = ($this->date_end !== null && $tmpDt = new DateTime($this->date_end)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_end = $newDateAsString;
                $this->modifiedColumns[] = DomainPeer::DATE_END;
            }
        } // if either are not null


        return $this;
    } // setDateEnd()

    /**
     * Set the value of [client_id] column.
     *
     * @param  int $v new value
     * @return Domain The current object (for fluent API support)
     */
    public function setClientId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->client_id !== $v) {
            $this->client_id = $v;
            $this->modifiedColumns[] = DomainPeer::CLIENT_ID;
        }

        if ($this->aClient !== null && $this->aClient->getId() !== $v) {
            $this->aClient = null;
        }


        return $this;
    } // setClientId()

    /**
     * Set the value of [nameserver_id] column.
     *
     * @param  int $v new value
     * @return Domain The current object (for fluent API support)
     */
    public function setNameserverId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nameserver_id !== $v) {
            $this->nameserver_id = $v;
            $this->modifiedColumns[] = DomainPeer::NAMESERVER_ID;
        }

        if ($this->aNameserver !== null && $this->aNameserver->getId() !== $v) {
            $this->aNameserver = null;
        }


        return $this;
    } // setNameserverId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Domain The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = DomainPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Domain The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = DomainPeer::UPDATED_AT;
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
            $this->date_start = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->date_end = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->client_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->nameserver_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = DomainPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Domain object", $e);
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
        if ($this->aNameserver !== null && $this->nameserver_id !== $this->aNameserver->getId()) {
            $this->aNameserver = null;
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
            $con = Propel::getConnection(DomainPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DomainPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aClient = null;
            $this->aNameserver = null;
            $this->collHostings = null;

            $this->collMailboxen = null;

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
            $con = Propel::getConnection(DomainPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DomainQuery::create()
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
            $con = Propel::getConnection(DomainPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                DomainPeer::addInstanceToPool($this);
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

            if ($this->aNameserver !== null) {
                if ($this->aNameserver->isModified() || $this->aNameserver->isNew()) {
                    $affectedRows += $this->aNameserver->save($con);
                }
                $this->setNameserver($this->aNameserver);
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

            if ($this->mailboxenScheduledForDeletion !== null) {
                if (!$this->mailboxenScheduledForDeletion->isEmpty()) {
                    foreach ($this->mailboxenScheduledForDeletion as $mailbox) {
                        // need to save related object because we set the relation to null
                        $mailbox->save($con);
                    }
                    $this->mailboxenScheduledForDeletion = null;
                }
            }

            if ($this->collMailboxen !== null) {
                foreach ($this->collMailboxen as $referrerFK) {
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

        $this->modifiedColumns[] = DomainPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DomainPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DomainPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DomainPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(DomainPeer::NOTES)) {
            $modifiedColumns[':p' . $index++]  = '`notes`';
        }
        if ($this->isColumnModified(DomainPeer::DATE_START)) {
            $modifiedColumns[':p' . $index++]  = '`date_start`';
        }
        if ($this->isColumnModified(DomainPeer::DATE_END)) {
            $modifiedColumns[':p' . $index++]  = '`date_end`';
        }
        if ($this->isColumnModified(DomainPeer::CLIENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`client_id`';
        }
        if ($this->isColumnModified(DomainPeer::NAMESERVER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`nameserver_id`';
        }
        if ($this->isColumnModified(DomainPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(DomainPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `domain` (%s) VALUES (%s)',
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
                    case '`date_start`':
                        $stmt->bindValue($identifier, $this->date_start, PDO::PARAM_STR);
                        break;
                    case '`date_end`':
                        $stmt->bindValue($identifier, $this->date_end, PDO::PARAM_STR);
                        break;
                    case '`client_id`':
                        $stmt->bindValue($identifier, $this->client_id, PDO::PARAM_INT);
                        break;
                    case '`nameserver_id`':
                        $stmt->bindValue($identifier, $this->nameserver_id, PDO::PARAM_INT);
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

            if ($this->aNameserver !== null) {
                if (!$this->aNameserver->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aNameserver->getValidationFailures());
                }
            }


            if (($retval = DomainPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collHostings !== null) {
                    foreach ($this->collHostings as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMailboxen !== null) {
                    foreach ($this->collMailboxen as $referrerFK) {
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
        $pos = DomainPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getDateStart();
                break;
            case 4:
                return $this->getDateEnd();
                break;
            case 5:
                return $this->getClientId();
                break;
            case 6:
                return $this->getNameserverId();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
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
        if (isset($alreadyDumpedObjects['Domain'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Domain'][$this->getPrimaryKey()] = true;
        $keys = DomainPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getNotes(),
            $keys[3] => $this->getDateStart(),
            $keys[4] => $this->getDateEnd(),
            $keys[5] => $this->getClientId(),
            $keys[6] => $this->getNameserverId(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aClient) {
                $result['Client'] = $this->aClient->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aNameserver) {
                $result['Nameserver'] = $this->aNameserver->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collHostings) {
                $result['Hostings'] = $this->collHostings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMailboxen) {
                $result['Mailboxen'] = $this->collMailboxen->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = DomainPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setDateStart($value);
                break;
            case 4:
                $this->setDateEnd($value);
                break;
            case 5:
                $this->setClientId($value);
                break;
            case 6:
                $this->setNameserverId($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
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
        $keys = DomainPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setNotes($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDateStart($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDateEnd($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setClientId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNameserverId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DomainPeer::DATABASE_NAME);

        if ($this->isColumnModified(DomainPeer::ID)) $criteria->add(DomainPeer::ID, $this->id);
        if ($this->isColumnModified(DomainPeer::NAME)) $criteria->add(DomainPeer::NAME, $this->name);
        if ($this->isColumnModified(DomainPeer::NOTES)) $criteria->add(DomainPeer::NOTES, $this->notes);
        if ($this->isColumnModified(DomainPeer::DATE_START)) $criteria->add(DomainPeer::DATE_START, $this->date_start);
        if ($this->isColumnModified(DomainPeer::DATE_END)) $criteria->add(DomainPeer::DATE_END, $this->date_end);
        if ($this->isColumnModified(DomainPeer::CLIENT_ID)) $criteria->add(DomainPeer::CLIENT_ID, $this->client_id);
        if ($this->isColumnModified(DomainPeer::NAMESERVER_ID)) $criteria->add(DomainPeer::NAMESERVER_ID, $this->nameserver_id);
        if ($this->isColumnModified(DomainPeer::CREATED_AT)) $criteria->add(DomainPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(DomainPeer::UPDATED_AT)) $criteria->add(DomainPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(DomainPeer::DATABASE_NAME);
        $criteria->add(DomainPeer::ID, $this->id);

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
     * @param object $copyObj An object of Domain (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setNotes($this->getNotes());
        $copyObj->setDateStart($this->getDateStart());
        $copyObj->setDateEnd($this->getDateEnd());
        $copyObj->setClientId($this->getClientId());
        $copyObj->setNameserverId($this->getNameserverId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getHostings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addHosting($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMailboxen() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMailbox($relObj->copy($deepCopy));
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
     * @return Domain Clone of current object.
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
     * @return DomainPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DomainPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Client object.
     *
     * @param                  Client $v
     * @return Domain The current object (for fluent API support)
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
            $v->addDomain($this);
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
                $this->aClient->addDomains($this);
             */
        }

        return $this->aClient;
    }

    /**
     * Declares an association between this object and a Nameserver object.
     *
     * @param                  Nameserver $v
     * @return Domain The current object (for fluent API support)
     * @throws PropelException
     */
    public function setNameserver(Nameserver $v = null)
    {
        if ($v === null) {
            $this->setNameserverId(NULL);
        } else {
            $this->setNameserverId($v->getId());
        }

        $this->aNameserver = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Nameserver object, it will not be re-added.
        if ($v !== null) {
            $v->addDomain($this);
        }


        return $this;
    }


    /**
     * Get the associated Nameserver object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Nameserver The associated Nameserver object.
     * @throws PropelException
     */
    public function getNameserver(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aNameserver === null && ($this->nameserver_id !== null) && $doQuery) {
            $this->aNameserver = NameserverQuery::create()->findPk($this->nameserver_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aNameserver->addDomains($this);
             */
        }

        return $this->aNameserver;
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
        if ('Hosting' == $relationName) {
            $this->initHostings();
        }
        if ('Mailbox' == $relationName) {
            $this->initMailboxen();
        }
    }

    /**
     * Clears out the collHostings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Domain The current object (for fluent API support)
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
     * If this Domain is new, it will return
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
                    ->filterByDomain($this)
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
     * @return Domain The current object (for fluent API support)
     */
    public function setHostings(PropelCollection $hostings, PropelPDO $con = null)
    {
        $hostingsToDelete = $this->getHostings(new Criteria(), $con)->diff($hostings);


        $this->hostingsScheduledForDeletion = $hostingsToDelete;

        foreach ($hostingsToDelete as $hostingRemoved) {
            $hostingRemoved->setDomain(null);
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
                ->filterByDomain($this)
                ->count($con);
        }

        return count($this->collHostings);
    }

    /**
     * Method called to associate a Hosting object to this object
     * through the Hosting foreign key attribute.
     *
     * @param    Hosting $l Hosting
     * @return Domain The current object (for fluent API support)
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
        $hosting->setDomain($this);
    }

    /**
     * @param	Hosting $hosting The hosting object to remove.
     * @return Domain The current object (for fluent API support)
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
            $hosting->setDomain(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Domain is new, it will return
     * an empty collection; or if this Domain has previously
     * been saved, it will retrieve related Hostings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Domain.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Hosting[] List of Hosting objects
     */
    public function getHostingsJoinHost($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostingQuery::create(null, $criteria);
        $query->joinWith('Host', $join_behavior);

        return $this->getHostings($query, $con);
    }

    /**
     * Clears out the collMailboxen collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Domain The current object (for fluent API support)
     * @see        addMailboxen()
     */
    public function clearMailboxen()
    {
        $this->collMailboxen = null; // important to set this to null since that means it is uninitialized
        $this->collMailboxenPartial = null;

        return $this;
    }

    /**
     * reset is the collMailboxen collection loaded partially
     *
     * @return void
     */
    public function resetPartialMailboxen($v = true)
    {
        $this->collMailboxenPartial = $v;
    }

    /**
     * Initializes the collMailboxen collection.
     *
     * By default this just sets the collMailboxen collection to an empty array (like clearcollMailboxen());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMailboxen($overrideExisting = true)
    {
        if (null !== $this->collMailboxen && !$overrideExisting) {
            return;
        }
        $this->collMailboxen = new PropelObjectCollection();
        $this->collMailboxen->setModel('Mailbox');
    }

    /**
     * Gets an array of Mailbox objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Domain is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Mailbox[] List of Mailbox objects
     * @throws PropelException
     */
    public function getMailboxen($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMailboxenPartial && !$this->isNew();
        if (null === $this->collMailboxen || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMailboxen) {
                // return empty collection
                $this->initMailboxen();
            } else {
                $collMailboxen = MailboxQuery::create(null, $criteria)
                    ->filterByDomain($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMailboxenPartial && count($collMailboxen)) {
                      $this->initMailboxen(false);

                      foreach ($collMailboxen as $obj) {
                        if (false == $this->collMailboxen->contains($obj)) {
                          $this->collMailboxen->append($obj);
                        }
                      }

                      $this->collMailboxenPartial = true;
                    }

                    $collMailboxen->getInternalIterator()->rewind();

                    return $collMailboxen;
                }

                if ($partial && $this->collMailboxen) {
                    foreach ($this->collMailboxen as $obj) {
                        if ($obj->isNew()) {
                            $collMailboxen[] = $obj;
                        }
                    }
                }

                $this->collMailboxen = $collMailboxen;
                $this->collMailboxenPartial = false;
            }
        }

        return $this->collMailboxen;
    }

    /**
     * Sets a collection of Mailbox objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $mailboxen A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Domain The current object (for fluent API support)
     */
    public function setMailboxen(PropelCollection $mailboxen, PropelPDO $con = null)
    {
        $mailboxenToDelete = $this->getMailboxen(new Criteria(), $con)->diff($mailboxen);


        $this->mailboxenScheduledForDeletion = $mailboxenToDelete;

        foreach ($mailboxenToDelete as $mailboxRemoved) {
            $mailboxRemoved->setDomain(null);
        }

        $this->collMailboxen = null;
        foreach ($mailboxen as $mailbox) {
            $this->addMailbox($mailbox);
        }

        $this->collMailboxen = $mailboxen;
        $this->collMailboxenPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Mailbox objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Mailbox objects.
     * @throws PropelException
     */
    public function countMailboxen(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMailboxenPartial && !$this->isNew();
        if (null === $this->collMailboxen || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMailboxen) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMailboxen());
            }
            $query = MailboxQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDomain($this)
                ->count($con);
        }

        return count($this->collMailboxen);
    }

    /**
     * Method called to associate a Mailbox object to this object
     * through the Mailbox foreign key attribute.
     *
     * @param    Mailbox $l Mailbox
     * @return Domain The current object (for fluent API support)
     */
    public function addMailbox(Mailbox $l)
    {
        if ($this->collMailboxen === null) {
            $this->initMailboxen();
            $this->collMailboxenPartial = true;
        }

        if (!in_array($l, $this->collMailboxen->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMailbox($l);

            if ($this->mailboxenScheduledForDeletion and $this->mailboxenScheduledForDeletion->contains($l)) {
                $this->mailboxenScheduledForDeletion->remove($this->mailboxenScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Mailbox $mailbox The mailbox object to add.
     */
    protected function doAddMailbox($mailbox)
    {
        $this->collMailboxen[]= $mailbox;
        $mailbox->setDomain($this);
    }

    /**
     * @param	Mailbox $mailbox The mailbox object to remove.
     * @return Domain The current object (for fluent API support)
     */
    public function removeMailbox($mailbox)
    {
        if ($this->getMailboxen()->contains($mailbox)) {
            $this->collMailboxen->remove($this->collMailboxen->search($mailbox));
            if (null === $this->mailboxenScheduledForDeletion) {
                $this->mailboxenScheduledForDeletion = clone $this->collMailboxen;
                $this->mailboxenScheduledForDeletion->clear();
            }
            $this->mailboxenScheduledForDeletion[]= $mailbox;
            $mailbox->setDomain(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->notes = null;
        $this->date_start = null;
        $this->date_end = null;
        $this->client_id = null;
        $this->nameserver_id = null;
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
            if ($this->collHostings) {
                foreach ($this->collHostings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMailboxen) {
                foreach ($this->collMailboxen as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aClient instanceof Persistent) {
              $this->aClient->clearAllReferences($deep);
            }
            if ($this->aNameserver instanceof Persistent) {
              $this->aNameserver->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collHostings instanceof PropelCollection) {
            $this->collHostings->clearIterator();
        }
        $this->collHostings = null;
        if ($this->collMailboxen instanceof PropelCollection) {
            $this->collMailboxen->clearIterator();
        }
        $this->collMailboxen = null;
        $this->aClient = null;
        $this->aNameserver = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DomainPeer::DEFAULT_STRING_FORMAT);
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
