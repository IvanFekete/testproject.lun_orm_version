<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="houses")
 **/
class House {
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
    /** @Column(type="string") **/
	protected $name;
    /**
     * @ManyToOne(targetEntity="Complex", inversedBy="houses")
     **/
	protected $complex;
	/**
     * @OneToMany(targetEntity="Flat", mappedBy="house")
     * @var Flat[] An ArrayCollection of Flat objects.
     **/
	protected $flats = null;
	
	public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function getComplex() {
        return $this->complex;
    }

    public function setComplex(Complex $complex = null) {
        if($complex != null) $complex->addHouse($this);
		$this->complex = $complex;
    }
	
	
	public function __construct() {
		$this->flats = new ArrayCollection();
	}
	
	public function addFlat(Flat $flat) {
		$this->flats[] = $flat;
	}
	
	public function getFlats() {
		return $this->flats;
	}
}
?>