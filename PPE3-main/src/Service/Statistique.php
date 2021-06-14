<?php

namespace App\Service;

use App\Repository\CompteRenduRepository;

class Statistique
{
	private CompteRenduRepository $compteRenduRepository;

	public function __construct(
		CompteRenduRepository $compteRenduRepository)
	{
		$this->compteRenduRepository = $compteRenduRepository;
	}

	public function determineMois()
	{
		$listeMois = [
			'01' => 0,
			'02' => 0,
			'03' => 0,
			'04' => 0,
			'05' => 0,
			'06' => 0,
			'07' => 0,
			'08' => 0,
			'09' => 0,
			'10' => 0,
			'11' => 0,
			'12' => 0
		];

		return $listeMois;
	}


	public function determineMedicamentsPresentesParMois($listeCompteRendu)
	{
		$medicamentsPresentes = $this->compteRenduRepository->findMedicamentsPresentes($listeCompteRendu);
		$medicamentsPresentesParMois = $this->determineMois();

		foreach($medicamentsPresentes as $medicament)
		{
			$moisVisite = date_format($medicament['dateVisite'], 'm');
			
			if(array_key_exists($moisVisite, $medicamentsPresentesParMois)){
				$medicamentsPresentesParMois[$moisVisite] += 1;
			}
		}
		// foreach($medicamentsPresentesParMois as $mois => $number){
		
			// setlocale (LC_TIME, 'fr_FR.utf8', 'fra'); 
			// $moisVisite = strftime("%B", $medicament['dateVisite']);
			// dump($moisVisite);
		// }

		return $medicamentsPresentesParMois;
	}

	public function determineMedicamentsOfferts($listeCompteRendu)
	{
		$medicamentsOfferts = $this->compteRenduRepository->findMedicamentsOfferts($listeCompteRendu);

		return $medicamentsOfferts;
	}


	public function determineMedicamentsOffertsParMois($listeCompteRendu)
	{
		$medicamentsOfferts = $this->determineMedicamentsOfferts($listeCompteRendu);
		$medicamentsOffertsParMois = $this->determineMois();

		foreach($medicamentsOfferts as $medicament)
		{
			$moisVisite = date_format($medicament['dateVisite'], 'm');
			
			if(array_key_exists($moisVisite, $medicamentsOffertsParMois)){
				$medicamentsOffertsParMois[$moisVisite] += 1;
			}
		}
		return $medicamentsOffertsParMois;
	}


	public function determineCoutMedicamentsOffertsParMois($listeCompteRendu)
	{
		$medicamentsOfferts = $this->determineMedicamentsOfferts($listeCompteRendu);
		$medicamentsOffertsParMois = $this->determineMois();

		foreach($medicamentsOfferts as $medicament)
		{
			$moisVisite = date_format($medicament['dateVisite'], 'm');
			
			if(array_key_exists($moisVisite, $medicamentsOffertsParMois)){
				$medicamentsOffertsParMois[$moisVisite] += ($medicament['quantiteOfferte'] * $medicament['prixEchantillon']) ;
			}
		}
		return $medicamentsOffertsParMois;
	}
}