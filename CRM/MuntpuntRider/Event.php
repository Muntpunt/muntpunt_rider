<?php

class CRM_MuntpuntRider_Event {
  public array $fields = [];

  public function __construct(int $eventId) {
    $event = $this->getEvent($eventId);
    $this->convertApiResultToFieldsArray($event);
  }

  private function getEvent($eventId) {
    return \Civi\Api4\Event::get(FALSE)
      ->addSelect('title', 'custom.*')
      ->addWhere('id', '=', $eventId)
      ->execute()
      ->first();
  }

  private function convertApiResultToFieldsArray($event) {
    if (empty($event)) {
      return;
    }

    $this->fields['id'] = $event['id'];
    $this->fields['title'] = $event['title'];
    $this->fields['meubilair'] = $this->convertfieldsMeubilair($event);
    $this->fields['catering'] = $this->convertfieldsCatering($event);
    $this->fields['technisch_materiaal'] = $this->convertfieldsTechnischMateriaal($event);
  }

  private function convertfieldsMeubilair($event) {
    $fieldNameAndTitle = [
      "RondeTafels" => "Rider_Meubilair_Technisch_materiaal.Ronde_Tafels",
      "RechthoekigetafelsPVC" => "Rider_Meubilair_Technisch_materiaal.Rechthoekige_tafels_PVC",
      "Rechthoekigetafelshout" => "Rider_Meubilair_Technisch_materiaal.Rechthoekige_tafels_hout",
      "Cocktailtafels" => "Rider_Meubilair_Technisch_materiaal.Cocktailtafels",
      "Conferentiestoelen" => "Rider_Meubilair_Technisch_materiaal.Conferentiestoelen",
      "Clubzetels" => "Rider_Meubilair_Technisch_materiaal.Clubzetels",
      "Klapstoelen" => "Rider_Meubilair_Technisch_materiaal.Klapstoelen",
      "Kartonnenkrukjes" => "Rider_Meubilair_Technisch_materiaal.Kartonnen_krukjes",
      "Kussens" => "Rider_Meubilair_Technisch_materiaal.Kussens",
      "Ontvangstbaliezonderfrigo" => "Rider_Meubilair_Technisch_materiaal.Ontvangstbalie_zonder_frigo_",
      "Vestiairerek" => "Rider_Meubilair_Technisch_materiaal.vestiairerek",
      "Flipchart" => "Rider_Meubilair_Technisch_materiaal.Flipchart",
      "Spreekgestoelte" => "Rider_Meubilair_Technisch_materiaal.Spreekgestoelte",
      "Tapijtgroot" => "Rider_Meubilair_Technisch_materiaal.Tapijt_groot_",
      "Tapijtklein" => "Rider_Meubilair_Technisch_materiaal.Tapijt_klein_",
      "Bijzettafel" => "Rider_Meubilair_Technisch_materiaal.Bijzettafel",
      "Podium" => "Rider_Meubilair_Technisch_materiaal.Podium",
      "Wittebankjes" => "Rider_Meubilair_Technisch_materiaal.Witte_bankjes",
      "Opstellingzaal" => "Rider_Meubilair_Technisch_materiaal.Opstelling_zaal",
      "Ontvangstbaliemetfrigo" => "Rider_Meubilair_Technisch_materiaal.Ontvangstbalie_met_frigo",
      "RidersEnkelindienmeerderezalentegelijk" => "Rider_Meubilair_Technisch_materiaal.Riders_Enkel_indien_meerdere_zalen_te_gelijk",
      "Opstellingtekening" => "Rider_Meubilair_Technisch_materiaal.Opstelling_tekening",
    ];

    return $this->extractNonEmptyfields($event, $fieldNameAndTitle);
  }

  private function convertfieldsCatering($event) {
    $fieldNameAndTitle = [
      "Koffie water en thee" => "Rider_Catering.Koffie_water_en_thee",
      "Koffie water en thee fruitsap" => "Rider_Catering.Koffie_water_en_thee_fruitsap",
      "Water fruitsap bier wijn en cava" => "Rider_Catering.Water_fruitsap_bier_wijn_en_cava",
      "Water" => "Rider_Catering.Water",
      "Koffiecorner bij vergaderzalen" => "Rider_Catering.Koffiecorner_bij_vergaderzalen",
    ];

    return $this->extractNonEmptyfields($event, $fieldNameAndTitle);
  }

  private function convertfieldsTechnischMateriaal($event) {
    $fieldNameAndTitle = [
      "Microfoon" => "Rider_Technisch_materiaal.Microfoon",
      "Aansluiting laptop" => "Rider_Technisch_materiaal.Aansluiting_laptop",
      "Microfoon headset" => "Rider_Technisch_materiaal.Microfoon_Headset",
      "Microfoonstatief" => "Rider_Technisch_materiaal.Microfoonstatief",
      "Stekkerdozen" => "Rider_Technisch_materiaal.Stekkerdozen",
      "Mobiele TV" => "Rider_Technisch_materiaal.Mobiele_TV",
      "Mobiele TV Hybride vergadering" => "Rider_Technisch_materiaal.Mobiele_TV_Hybride_vergadering",
      "Mobiele speakers" => "Rider_Technisch_materiaal.Mobiele_speakers",
      "Theaterspot" => "Rider_Technisch_materiaal.Theaterspot",
    ];

    return $this->extractNonEmptyfields($event, $fieldNameAndTitle);
  }

  private function extractNonEmptyfields($event, $fieldNameAndTitle) {
    $arr = [];
    foreach ($fieldNameAndTitle as $fieldName => $title) {
      if (!empty($event[$fieldName])) {
        $arr[$title] = $event[$fieldName];
      }
    }

    return $arr;
  }

}
