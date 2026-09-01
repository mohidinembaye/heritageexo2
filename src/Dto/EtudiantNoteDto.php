<?php 

final class EtudiantNoteDto{
     private int $id;
    private \DateTimeImmutable $dateDepot;
  public function setId(int $id){
    $this->id=$id;

  }
   public function getId(){
    $this->id;
  }
   public function setDateDepot(\DateTimeImmutable $dateDepot){
    $this->dateDepot=$dateDepot;

  }
   public function getDateDepot(){
    $this->dateDepot;
  }


}