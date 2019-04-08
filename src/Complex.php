<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="complexes")
 **/
class Complex {
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
    /** @Column(type="string") **/
	protected $name;
    /** @Column(type="string") **/
	protected $city;
	/**
     * @OneToMany(targetEntity="House", mappedBy="complex")
     * @var House[] An ArrayCollection of House objects.
     **/
	protected $houses = null;
	
	
	public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }
	
	public function __construct() {
		$this->houses = new ArrayCollection();
	}
	
	public function addHouse(House $house) {
		$this->houses[] = $house;
	}
	
	public function getHouses() {
		return $this->houses;
	}
}
?>