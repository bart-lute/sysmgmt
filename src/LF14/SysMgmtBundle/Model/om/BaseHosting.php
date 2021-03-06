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
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use LF14\SysMgmtBundle\Model\Domain;
use LF14\SysMgmtBundle\Model\DomainQuery;
use LF14\SysMgmtBundle\Model\Host;
use LF14\SysMgmtBundle\Model\HostQuery;
use LF14\SysMgmtBundle\Model\Hosting;
use LF14\SysMgmtBundle\Model\HostingPeer;
use LF14\SysMgmtBundle\Model\HostingQuery;

abstract class BaseHosting extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'LF14\\SysMgmtBundle\\Model\\HostingPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        HostingPeer
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
     * The value for the url field.
     * @var        string
     */
    protected $url;

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
     * The value for the domain_id field.
     * @var        int
     */
    protected $domain_id;

    /**
     * The value for the host_id field.
     * @var        int
     */
    protected $host_id;

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
     * @var        Domain
     */
    protected $aDomain;

    /**
     * @var        Host
     */
    protected $aHost;

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
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
    }

    /**
     * Initializes internal state of BaseHosting object.
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
     * Get the [url] column value.
     *
     * @return string
     */
    public function getUrl()
    {

        return $this->url;
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
     * Get the [domain_id] column value.
     *
     * @return int
     */
    public function getDomainId()
    {

        return $this->domain_id;
    }

    /**
     * Get the [host_id] column value.
     *
     * @return int
     */
    public function getHostId()
    {

        return $this->host_id;
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
     * @return Hosting The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = HostingPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [url] column.
     *
     * @param  string $v new value
     * @return Hosting The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[] = HostingPeer::URL;
        }


        return $this;
    } // setUrl()

    /**
     * Set the value of [notes] column.
     *
     * @param  string $v new value
     * @return Hosting The current object (for fluent API support)
     */
    public function setNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notes !== $v) {
            $this->notes = $v;
            $this->modifiedColumns[] = HostingPeer::NOTES;
        }


        return $this;
    } // setNotes()

    /**
     * Sets the value of [date_start] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Hosting The current object (for fluent API support)
     */
    public function setDateStart($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_start !== null || $dt !== null) {
            $currentDateAsString = ($this->date_start !== null && $tmpDt = new DateTime($this->date_start)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_start = $newDateAsString;
                $this->modifiedColumns[] = HostingPeer::DATE_START;
            }
        } // if either are not null


        return $this;
    } // setDateStart()

    /**
     * Sets the value of [date_end] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Hosting The current object (for fluent API support)
     */
    public function setDateEnd($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_end !== null || $dt !== null) {
            $currentDateAsString = ($this->date_end !== null && $tmpDt = new DateTime($this->date_end)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date_end = $newDateAsString;
                $this->modifiedColumns[] = HostingPeer::DATE_END;
            }
        } // if either are not null


        return $this;
    } // setDateEnd()

    /**
     * Set the value of [domain_id] column.
     *
     * @param  int $v new value
     * @return Hosting The current object (for fluent API support)
     */
    public function setDomainId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->domain_id !== $v) {
            $this->domain_id = $v;
            $this->modifiedColumns[] = HostingPeer::DOMAIN_ID;
        }

        if ($this->aDomain !== null && $this->aDomain->getId() !== $v) {
            $this->aDomain = null;
        }


        return $this;
    } // setDomainId()

    /**
     * Set the value of [host_id] column.
     *
     * @param  int $v new value
     * @return Hosting The current object (for fluent API support)
     */
    public function setHostId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->host_id !== $v) {
            $this->host_id = $v;
            $this->modifiedColumns[] = HostingPeer::HOST_ID;
        }

        if ($this->aHost !== null && $this->aHost->getId() !== $v) {
            $this->aHost = null;
        }


        return $this;
    } // setHostId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Hosting The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = HostingPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Hosting The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = HostingPeer::UPDATED_AT;
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
            $this->url = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->notes = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->date_start = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->date_end = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->domain_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->host_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = HostingPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Hosting object", $e);
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

        if ($this->aDomain !== null && $this->domain_id !== $this->aDomain->getId()) {
            $this->aDomain = null;
        }
        if ($this->aHost !== null && $this->host_id !== $this->aHost->getId()) {
            $this->aHost = null;
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
            $con = Propel::getConnection(HostingPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = HostingPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aDomain = null;
            $this->aHost = null;
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
            $con = Propel::getConnection(HostingPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = HostingQuery::create()
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
            $con = Propel::getConnection(HostingPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                HostingPeer::addInstanceToPool($this);
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

            if ($this->aDomain !== null) {
                if ($this->aDomain->isModified() || $this->aDomain->isNew()) {
                    $affectedRows += $this->aDomain->save($con);
                }
                $this->setDomain($this->aDomain);
            }

            if ($this->aHost !== null) {
                if ($this->aHost->isModified() || $this->aHost->isNew()) {
                    $affectedRows += $this->aHost->save($con);
                }
                $this->setHost($this->aHost);
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

        $this->modifiedColumns[] = HostingPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . HostingPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(HostingPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(HostingPeer::URL)) {
            $modifiedColumns[':p' . $index++]  = '`url`';
        }
        if ($this->isColumnModified(HostingPeer::NOTES)) {
            $modifiedColumns[':p' . $index++]  = '`notes`';
        }
        if ($this->isColumnModified(HostingPeer::DATE_START)) {
            $modifiedColumns[':p' . $index++]  = '`date_start`';
        }
        if ($this->isColumnModified(HostingPeer::DATE_END)) {
            $modifiedColumns[':p' . $index++]  = '`date_end`';
        }
        if ($this->isColumnModified(HostingPeer::DOMAIN_ID)) {
            $modifiedColumns[':p' . $index++]  = '`domain_id`';
        }
        if ($this->isColumnModified(HostingPeer::HOST_ID)) {
            $modifiedColumns[':p' . $index++]  = '`host_id`';
        }
        if ($this->isColumnModified(HostingPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(HostingPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `hosting` (%s) VALUES (%s)',
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
                    case '`url`':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
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
                    case '`domain_id`':
                        $stmt->bindValue($identifier, $this->domain_id, PDO::PARAM_INT);
                        break;
                    case '`host_id`':
                        $stmt->bindValue($identifier, $this->host_id, PDO::PARAM_INT);
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

            if ($this->aDomain !== null) {
                if (!$this->aDomain->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDomain->getValidationFailures());
                }
            }

            if ($this->aHost !== null) {
                if (!$this->aHost->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aHost->getValidationFailures());
                }
            }


            if (($retval = HostingPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = HostingPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUrl();
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
                return $this->getDomainId();
                break;
            case 6:
                return $this->getHostId();
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
        if (isset($alreadyDumpedObjects['Hosting'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Hosting'][$this->getPrimaryKey()] = true;
        $keys = HostingPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUrl(),
            $keys[2] => $this->getNotes(),
            $keys[3] => $this->getDateStart(),
            $keys[4] => $this->getDateEnd(),
            $keys[5] => $this->getDomainId(),
            $keys[6] => $this->getHostId(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aDomain) {
                $result['Domain'] = $this->aDomain->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aHost) {
                $result['Host'] = $this->aHost->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = HostingPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUrl($value);
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
                $this->setDomainId($value);
                break;
            case 6:
                $this->setHostId($value);
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
        $keys = HostingPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUrl($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setNotes($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDateStart($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDateEnd($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDomainId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setHostId($arr[$keys[6]]);
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
        $criteria = new Criteria(HostingPeer::DATABASE_NAME);

        if ($this->isColumnModified(HostingPeer::ID)) $criteria->add(HostingPeer::ID, $this->id);
        if ($this->isColumnModified(HostingPeer::URL)) $criteria->add(HostingPeer::URL, $this->url);
        if ($this->isColumnModified(HostingPeer::NOTES)) $criteria->add(HostingPeer::NOTES, $this->notes);
        if ($this->isColumnModified(HostingPeer::DATE_START)) $criteria->add(HostingPeer::DATE_START, $this->date_start);
        if ($this->isColumnModified(HostingPeer::DATE_END)) $criteria->add(HostingPeer::DATE_END, $this->date_end);
        if ($this->isColumnModified(HostingPeer::DOMAIN_ID)) $criteria->add(HostingPeer::DOMAIN_ID, $this->domain_id);
        if ($this->isColumnModified(HostingPeer::HOST_ID)) $criteria->add(HostingPeer::HOST_ID, $this->host_id);
        if ($this->isColumnModified(HostingPeer::CREATED_AT)) $criteria->add(HostingPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(HostingPeer::UPDATED_AT)) $criteria->add(HostingPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(HostingPeer::DATABASE_NAME);
        $criteria->add(HostingPeer::ID, $this->id);

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
     * @param object $copyObj An object of Hosting (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUrl($this->getUrl());
        $copyObj->setNotes($this->getNotes());
        $copyObj->setDateStart($this->getDateStart());
        $copyObj->setDateEnd($this->getDateEnd());
        $copyObj->setDomainId($this->getDomainId());
        $copyObj->setHostId($this->getHostId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return Hosting Clone of current object.
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
     * @return HostingPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new HostingPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Domain object.
     *
     * @param                  Domain $v
     * @return Hosting The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDomain(Domain $v = null)
    {
        if ($v === null) {
            $this->setDomainId(NULL);
        } else {
            $this->setDomainId($v->getId());
        }

        $this->aDomain = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Domain object, it will not be re-added.
        if ($v !== null) {
            $v->addHosting($this);
        }


        return $this;
    }


    /**
     * Get the associated Domain object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Domain The associated Domain object.
     * @throws PropelException
     */
    public function getDomain(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDomain === null && ($this->domain_id !== null) && $doQuery) {
            $this->aDomain = DomainQuery::create()->findPk($this->domain_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDomain->addHostings($this);
             */
        }

        return $this->aDomain;
    }

    /**
     * Declares an association between this object and a Host object.
     *
     * @param                  Host $v
     * @return Hosting The current object (for fluent API support)
     * @throws PropelException
     */
    public function setHost(Host $v = null)
    {
        if ($v === null) {
            $this->setHostId(NULL);
        } else {
            $this->setHostId($v->getId());
        }

        $this->aHost = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Host object, it will not be re-added.
        if ($v !== null) {
            $v->addHosting($this);
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
    public function getHost(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aHost === null && ($this->host_id !== null) && $doQuery) {
            $this->aHost = HostQuery::create()->findPk($this->host_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aHost->addHostings($this);
             */
        }

        return $this->aHost;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->url = null;
        $this->notes = null;
        $this->date_start = null;
        $this->date_end = null;
        $this->domain_id = null;
        $this->host_id = null;
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
            if ($this->aDomain instanceof Persistent) {
              $this->aDomain->clearAllReferences($deep);
            }
            if ($this->aHost instanceof Persistent) {
              $this->aHost->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aDomain = null;
        $this->aHost = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(HostingPeer::DEFAULT_STRING_FORMAT);
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
