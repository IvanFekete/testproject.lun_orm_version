<?php
/**
 * @Entity @Table(name="flats")
 **/
class Flat {
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
    /** @Column(type="float") **/
	protected $square;
    /** @Column(type="integer") **/
	protected $price;
    /**
     * @ManyToOne(targetEntity="FlatType", inversedBy="flats")
     **/
	protected $flatType;
    /**
     * @ManyToOne(targetEntity="House", inversedBy="flats")
     **/
	protected $house;
	
	public function getId() {
        return $this->id;
    }

    public function getSquare() {
        return $this->square;
    }

    public function setSquare($square) {
        $this->square = $square;
    }
	
    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }
    
	public function getFlatType() {
        return $this->flatType;
    }

    public function setFlatType(FlatType $flatType = null) {
        if($flatType != null) $flatType->addFlat($this);
		$this->flatType = $flatType;
    }
    public function getHouse() {
        return $this->house;
    }

    public function setHouse(House $house = null) {
        if($house != null) $house->addFlat($this);
		$this->house = $house;
    }
}
?>