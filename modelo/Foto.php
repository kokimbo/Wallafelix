<?php 
class Foto
{
    private $id;
    private $foto;
    private $idAnuncio;
    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of foto
     */ 
    public function getFotoAnuncio()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */ 
    public function setFotoAnuncio($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get the value of idAnuncio
     */ 
    public function getIdAnuncio()
    {
        return $this->idAnuncio;
    }

    /**
     * Set the value of idAnuncio
     *
     * @return  self
     */ 
    public function setIdAnuncio($idAnuncio)
    {
        $this->idAnuncio = $idAnuncio;

        return $this;
    }
}

?>