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
     /**
     * @ManyToOne(targetEntity="City", inversedBy="complexes")
     **/
	protected $city;
	/**
     * @OneToMany(targetEntity="House", mappedBy="complex")
     * @var House[] An ArrayCollection of House objects.
     **/
	protected $houses = null;
	
	  /**
     * Many Complexes have Many Localities.
     * @ManyToMany(targetEntity="Locality", inversedBy="complexes")
     * @JoinTable(name="localities_complexes")
     */
	protected $localities = null;
	
	
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

    public function setCity(City $city) {
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
	
	
	public function addLocality(Locality $locality) {
		$this->localities[] = $locality;
		$locality->addComplex($this);
	}
	
	public function getLocalities() {
		return $this->localities;
	}
	
	public function removeLocality(Locality $locality) {
		$this->localities->removeElement($locality);
		$locality->removeComplex($this);
	}
}
?>