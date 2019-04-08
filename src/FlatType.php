<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="flat_types")
 **/
class FlatType {
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
    /** @Column(type="string") **/
	protected $name;
	/**
     * @OneToMany(targetEntity="Flat", mappedBy="flatType")
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