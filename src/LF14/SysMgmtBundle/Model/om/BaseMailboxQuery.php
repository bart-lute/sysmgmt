<?php

namespace LF14\SysMgmtBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use LF14\SysMgmtBundle\Model\Domain;
use LF14\SysMgmtBundle\Model\Mailbox;
use LF14\SysMgmtBundle\Model\MailboxPeer;
use LF14\SysMgmtBundle\Model\MailboxQuery;

/**
 * @method MailboxQuery orderById($order = Criteria::ASC) Order by the id column
 * @method MailboxQuery orderByDomainId($order = Criteria::ASC) Order by the domain_id column
 * @method MailboxQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method MailboxQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method MailboxQuery orderByDateStart($order = Criteria::ASC) Order by the date_start column
 * @method MailboxQuery orderByDateEnd($order = Criteria::ASC) Order by the date_end column
 * @method MailboxQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method MailboxQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method MailboxQuery groupById() Group by the id column
 * @method MailboxQuery groupByDomainId() Group by the domain_id column
 * @method MailboxQuery groupByUsername() Group by the username column
 * @method MailboxQuery groupByPassword() Group by the password column
 * @method MailboxQuery groupByDateStart() Group by the date_start column
 * @method MailboxQuery groupByDateEnd() Group by the date_end column
 * @method MailboxQuery groupByCreatedAt() Group by the created_at column
 * @method MailboxQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method MailboxQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method MailboxQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method MailboxQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method MailboxQuery leftJoinDomain($relationAlias = null) Adds a LEFT JOIN clause to the query using the Domain relation
 * @method MailboxQuery rightJoinDomain($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Domain relation
 * @method MailboxQuery innerJoinDomain($relationAlias = null) Adds a INNER JOIN clause to the query using the Domain relation
 *
 * @method Mailbox findOne(PropelPDO $con = null) Return the first Mailbox matching the query
 * @method Mailbox findOneOrCreate(PropelPDO $con = null) Return the first Mailbox matching the query, or a new Mailbox object populated from the query conditions when no match is found
 *
 * @method Mailbox findOneByDomainId(int $domain_id) Return the first Mailbox filtered by the domain_id column
 * @method Mailbox findOneByUsername(string $username) Return the first Mailbox filtered by the username column
 * @method Mailbox findOneByPassword(string $password) Return the first Mailbox filtered by the password column
 * @method Mailbox findOneByDateStart(string $date_start) Return the first Mailbox filtered by the date_start column
 * @method Mailbox findOneByDateEnd(string $date_end) Return the first Mailbox filtered by the date_end column
 * @method Mailbox findOneByCreatedAt(string $created_at) Return the first Mailbox filtered by the created_at column
 * @method Mailbox findOneByUpdatedAt(string $updated_at) Return the first Mailbox filtered by the updated_at column
 *
 * @method array findById(int $id) Return Mailbox objects filtered by the id column
 * @method array findByDomainId(int $domain_id) Return Mailbox objects filtered by the domain_id column
 * @method array findByUsername(string $username) Return Mailbox objects filtered by the username column
 * @method array findByPassword(string $password) Return Mailbox objects filtered by the password column
 * @method array findByDateStart(string $date_start) Return Mailbox objects filtered by the date_start column
 * @method array findByDateEnd(string $date_end) Return Mailbox objects filtered by the date_end column
 * @method array findByCreatedAt(string $created_at) Return Mailbox objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Mailbox objects filtered by the updated_at column
 */
abstract class BaseMailboxQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseMailboxQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'LF14\\SysMgmtBundle\\Model\\Mailbox';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new MailboxQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   MailboxQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return MailboxQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof MailboxQuery) {
            return $criteria;
        }
        $query = new MailboxQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Mailbox|Mailbox[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MailboxPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(MailboxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Mailbox A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Mailbox A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `domain_id`, `username`, `password`, `date_start`, `date_end`, `created_at`, `updated_at` FROM `mailbox` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Mailbox();
            $obj->hydrate($row);
            MailboxPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Mailbox|Mailbox[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Mailbox[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MailboxPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MailboxPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MailboxPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MailboxPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailboxPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the domain_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDomainId(1234); // WHERE domain_id = 1234
     * $query->filterByDomainId(array(12, 34)); // WHERE domain_id IN (12, 34)
     * $query->filterByDomainId(array('min' => 12)); // WHERE domain_id >= 12
     * $query->filterByDomainId(array('max' => 12)); // WHERE domain_id <= 12
     * </code>
     *
     * @see       filterByDomain()
     *
     * @param     mixed $domainId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByDomainId($domainId = null, $comparison = null)
    {
        if (is_array($domainId)) {
            $useMinMax = false;
            if (isset($domainId['min'])) {
                $this->addUsingAlias(MailboxPeer::DOMAIN_ID, $domainId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($domainId['max'])) {
                $this->addUsingAlias(MailboxPeer::DOMAIN_ID, $domainId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailboxPeer::DOMAIN_ID, $domainId, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailboxPeer::USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailboxPeer::PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the date_start column
     *
     * Example usage:
     * <code>
     * $query->filterByDateStart('2011-03-14'); // WHERE date_start = '2011-03-14'
     * $query->filterByDateStart('now'); // WHERE date_start = '2011-03-14'
     * $query->filterByDateStart(array('max' => 'yesterday')); // WHERE date_start < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateStart The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByDateStart($dateStart = null, $comparison = null)
    {
        if (is_array($dateStart)) {
            $useMinMax = false;
            if (isset($dateStart['min'])) {
                $this->addUsingAlias(MailboxPeer::DATE_START, $dateStart['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateStart['max'])) {
                $this->addUsingAlias(MailboxPeer::DATE_START, $dateStart['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailboxPeer::DATE_START, $dateStart, $comparison);
    }

    /**
     * Filter the query on the date_end column
     *
     * Example usage:
     * <code>
     * $query->filterByDateEnd('2011-03-14'); // WHERE date_end = '2011-03-14'
     * $query->filterByDateEnd('now'); // WHERE date_end = '2011-03-14'
     * $query->filterByDateEnd(array('max' => 'yesterday')); // WHERE date_end < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateEnd The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByDateEnd($dateEnd = null, $comparison = null)
    {
        if (is_array($dateEnd)) {
            $useMinMax = false;
            if (isset($dateEnd['min'])) {
                $this->addUsingAlias(MailboxPeer::DATE_END, $dateEnd['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateEnd['max'])) {
                $this->addUsingAlias(MailboxPeer::DATE_END, $dateEnd['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailboxPeer::DATE_END, $dateEnd, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MailboxPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MailboxPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailboxPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MailboxPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MailboxPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailboxPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Domain object
     *
     * @param   Domain|PropelObjectCollection $domain The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MailboxQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDomain($domain, $comparison = null)
    {
        if ($domain instanceof Domain) {
            return $this
                ->addUsingAlias(MailboxPeer::DOMAIN_ID, $domain->getId(), $comparison);
        } elseif ($domain instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MailboxPeer::DOMAIN_ID, $domain->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDomain() only accepts arguments of type Domain or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Domain relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function joinDomain($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Domain');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Domain');
        }

        return $this;
    }

    /**
     * Use the Domain relation Domain object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\DomainQuery A secondary query class using the current class as primary query
     */
    public function useDomainQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDomain($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Domain', '\LF14\SysMgmtBundle\Model\DomainQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Mailbox $mailbox Object to remove from the list of results
     *
     * @return MailboxQuery The current query, for fluid interface
     */
    public function prune($mailbox = null)
    {
        if ($mailbox) {
            $this->addUsingAlias(MailboxPeer::ID, $mailbox->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
