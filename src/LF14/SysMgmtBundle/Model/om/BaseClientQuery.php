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
use LF14\SysMgmtBundle\Model\Client;
use LF14\SysMgmtBundle\Model\ClientContact;
use LF14\SysMgmtBundle\Model\ClientPeer;
use LF14\SysMgmtBundle\Model\ClientQuery;
use LF14\SysMgmtBundle\Model\Domain;
use LF14\SysMgmtBundle\Model\Host;

/**
 * @method ClientQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ClientQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method ClientQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method ClientQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method ClientQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method ClientQuery groupById() Group by the id column
 * @method ClientQuery groupByName() Group by the name column
 * @method ClientQuery groupByNotes() Group by the notes column
 * @method ClientQuery groupByCreatedAt() Group by the created_at column
 * @method ClientQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method ClientQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ClientQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ClientQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ClientQuery leftJoinClientContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the ClientContact relation
 * @method ClientQuery rightJoinClientContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ClientContact relation
 * @method ClientQuery innerJoinClientContact($relationAlias = null) Adds a INNER JOIN clause to the query using the ClientContact relation
 *
 * @method ClientQuery leftJoinDomain($relationAlias = null) Adds a LEFT JOIN clause to the query using the Domain relation
 * @method ClientQuery rightJoinDomain($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Domain relation
 * @method ClientQuery innerJoinDomain($relationAlias = null) Adds a INNER JOIN clause to the query using the Domain relation
 *
 * @method ClientQuery leftJoinHost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Host relation
 * @method ClientQuery rightJoinHost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Host relation
 * @method ClientQuery innerJoinHost($relationAlias = null) Adds a INNER JOIN clause to the query using the Host relation
 *
 * @method Client findOne(PropelPDO $con = null) Return the first Client matching the query
 * @method Client findOneOrCreate(PropelPDO $con = null) Return the first Client matching the query, or a new Client object populated from the query conditions when no match is found
 *
 * @method Client findOneByName(string $name) Return the first Client filtered by the name column
 * @method Client findOneByNotes(string $notes) Return the first Client filtered by the notes column
 * @method Client findOneByCreatedAt(string $created_at) Return the first Client filtered by the created_at column
 * @method Client findOneByUpdatedAt(string $updated_at) Return the first Client filtered by the updated_at column
 *
 * @method array findById(int $id) Return Client objects filtered by the id column
 * @method array findByName(string $name) Return Client objects filtered by the name column
 * @method array findByNotes(string $notes) Return Client objects filtered by the notes column
 * @method array findByCreatedAt(string $created_at) Return Client objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Client objects filtered by the updated_at column
 */
abstract class BaseClientQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseClientQuery object.
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
            $modelName = 'LF14\\SysMgmtBundle\\Model\\Client';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ClientQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ClientQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ClientQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ClientQuery) {
            return $criteria;
        }
        $query = new ClientQuery(null, null, $modelAlias);

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
     * @return   Client|Client[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ClientPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ClientPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Client A model object, or null if the key is not found
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
     * @return                 Client A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `notes`, `created_at`, `updated_at` FROM `client` WHERE `id` = :p0';
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
            $obj = new Client();
            $obj->hydrate($row);
            ClientPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Client|Client[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Client[]|mixed the list of results, formatted by the current formatter
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
     * @return ClientQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ClientPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ClientQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ClientPeer::ID, $keys, Criteria::IN);
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
     * @return ClientQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ClientPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ClientPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ClientQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ClientPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the notes column
     *
     * Example usage:
     * <code>
     * $query->filterByNotes('fooValue');   // WHERE notes = 'fooValue'
     * $query->filterByNotes('%fooValue%'); // WHERE notes LIKE '%fooValue%'
     * </code>
     *
     * @param     string $notes The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ClientQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $notes)) {
                $notes = str_replace('*', '%', $notes);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ClientPeer::NOTES, $notes, $comparison);
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
     * @return ClientQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ClientPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ClientPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return ClientQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ClientPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ClientPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related ClientContact object
     *
     * @param   ClientContact|PropelObjectCollection $clientContact  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ClientQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByClientContact($clientContact, $comparison = null)
    {
        if ($clientContact instanceof ClientContact) {
            return $this
                ->addUsingAlias(ClientPeer::ID, $clientContact->getClientId(), $comparison);
        } elseif ($clientContact instanceof PropelObjectCollection) {
            return $this
                ->useClientContactQuery()
                ->filterByPrimaryKeys($clientContact->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByClientContact() only accepts arguments of type ClientContact or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ClientContact relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ClientQuery The current query, for fluid interface
     */
    public function joinClientContact($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ClientContact');

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
            $this->addJoinObject($join, 'ClientContact');
        }

        return $this;
    }

    /**
     * Use the ClientContact relation ClientContact object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\ClientContactQuery A secondary query class using the current class as primary query
     */
    public function useClientContactQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinClientContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ClientContact', '\LF14\SysMgmtBundle\Model\ClientContactQuery');
    }

    /**
     * Filter the query by a related Domain object
     *
     * @param   Domain|PropelObjectCollection $domain  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ClientQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDomain($domain, $comparison = null)
    {
        if ($domain instanceof Domain) {
            return $this
                ->addUsingAlias(ClientPeer::ID, $domain->getClientId(), $comparison);
        } elseif ($domain instanceof PropelObjectCollection) {
            return $this
                ->useDomainQuery()
                ->filterByPrimaryKeys($domain->getPrimaryKeys())
                ->endUse();
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
     * @return ClientQuery The current query, for fluid interface
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
     * Filter the query by a related Host object
     *
     * @param   Host|PropelObjectCollection $host  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ClientQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHost($host, $comparison = null)
    {
        if ($host instanceof Host) {
            return $this
                ->addUsingAlias(ClientPeer::ID, $host->getClientId(), $comparison);
        } elseif ($host instanceof PropelObjectCollection) {
            return $this
                ->useHostQuery()
                ->filterByPrimaryKeys($host->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByHost() only accepts arguments of type Host or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Host relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ClientQuery The current query, for fluid interface
     */
    public function joinHost($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Host');

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
            $this->addJoinObject($join, 'Host');
        }

        return $this;
    }

    /**
     * Use the Host relation Host object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostQuery A secondary query class using the current class as primary query
     */
    public function useHostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinHost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Host', '\LF14\SysMgmtBundle\Model\HostQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Client $client Object to remove from the list of results
     *
     * @return ClientQuery The current query, for fluid interface
     */
    public function prune($client = null)
    {
        if ($client) {
            $this->addUsingAlias(ClientPeer::ID, $client->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
