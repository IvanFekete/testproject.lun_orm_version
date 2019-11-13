<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="localities")
 **/
class Locality {
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
    /** @Column(type="string") **/
	protected $name;
     /**
     * @ManyToMany(targetEntity="Complex", mappedBy="localities")
     */
	protected $complexes = null;
	
	
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
		$this->complexes = new ArrayCollection();
	}
	
	public function addComplex(Complex $complex) {
		$this->complexes[] = $complex;
	}
	
	public function removeComplex(Complex $complex) {
		$this->complexes->removeElement($complex);
	}
	
	public function getComplexes() {
		return $this->complexes;
	}
}
?>