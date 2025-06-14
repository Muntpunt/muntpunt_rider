<?php

class CRM_MuntpuntRider_Event {
  public array $fields = [];

  public function __construct(int $eventId) {
    $event = $this->getEvent($eventId);
    $this->convertApiResultToFieldsArray($event);
  }

  private function getEvent($eventId) {
    $event = \Civi\Api4\Event::get(FALSE)
      ->addSelect('title', 'start_date', 'end_date', 'custom.*',
        'extra_evenement_info.muntpunt_zalen:label', 'evenement_planning_memo_overleg_en_statistiek.aanpreekpersoon.display_name',
        'Rider_Meubilair_Technisch_materiaal.Opstelling_zaal:label',
        'Rider_Technisch_materiaal.Aansluiting_laptop:label',
        'Rider_Catering.Koffiecorner_bij_vergaderzalen:label'
      )
      ->addWhere('id', '=', $eventId)
      ->execute()
      ->first();

    if ($event && $this->isInRoomOfInterest($event)) {
      return $event;
    }
    else {
      return null;
    }
  }

  private function convertApiResultToFieldsArray($event) {
    if (empty($event)) {
      return;
    }

    $this->fields['id'] = $event['id'];
    $this->fields['title'] = $event['title'];
    $this->fields['start_hour'] = substr($event['start_date'], 11, 5);
    $this->fields['end_hour'] = substr($event['end_date'], 11, 5);
    $this->fields['zalen'] = implode(', ', $event['extra_evenement_info.muntpunt_zalen:label']);
    $this->fields['aanspreekpersoon'] = $event['evenement_planning_memo_overleg_en_statistiek.aanpreekpersoon.display_name'];
    $this->fields['meubilair'] = $this->convertfieldsMeubilair($event);
    $this->fields['catering'] = $this->convertfieldsCatering($event);
    $this->fields['technisch_materiaal'] = $this->convertfieldsTechnischMateriaal($event);
  }

  private function convertfieldsMeubilair($event) {
    $fieldNameAndTitle = [
      "Rider" => "Rider_Meubilair_Technisch_materiaal.Riders_Enkel_indien_meerdere_zalen_te_gelijk",
      "Opstelling" => "Rider_Meubilair_Technisch_materiaal.Opstelling_tekening",
      "Opstelling zaal" => "Rider_Meubilair_Technisch_materiaal.Opstelling_zaal:label",
      "Ronde tafels" => "Rider_Meubilair_Technisch_materiaal.Ronde_Tafels",
      "Rechthoekige tafels PVC" => "Rider_Meubilair_Technisch_materiaal.Rechthoekige_tafels_PVC",
      "Rechthoekige tafels hout" => "Rider_Meubilair_Technisch_materiaal.Rechthoekige_tafels_hout",
      "Cocktailtafels" => "Rider_Meubilair_Technisch_materiaal.Cocktailtafels",
      "Conferentiestoelen" => "Rider_Meubilair_Technisch_materiaal.Conferentiestoelen",
      "Clubzetels" => "Rider_Meubilair_Technisch_materiaal.Clubzetels",
      "Klapstoelen" => "Rider_Meubilair_Technisch_materiaal.Klapstoelen",
      "Kartonnen krukjes" => "Rider_Meubilair_Technisch_materiaal.Kartonnen_krukjes",
      "Kussens" => "Rider_Meubilair_Technisch_materiaal.Kussens",
      "Witte bankjes" => "Rider_Meubilair_Technisch_materiaal.Witte_bankjes",
      "Hoge stoelen" => "Rider_Meubilair_Technisch_materiaal.Hoge_stoelen",
      "Ontvangstbalie (zonder frigo)" => "Rider_Meubilair_Technisch_materiaal.Ontvangstbalie_zonder_frigo_",
      "Ontvangstbalie (met frigo)" => "Rider_Meubilair_Technisch_materiaal.Ontvangstbalie_met_frigo",
      "Flipchart" => "Rider_Meubilair_Technisch_materiaal.Flipchart",
      "Vestiairerek" => "Rider_Meubilair_Technisch_materiaal.vestiairerek",
      "Spreekgestoelte" => "Rider_Meubilair_Technisch_materiaal.Spreekgestoelte",
      "Tapijt (groot)" => "Rider_Meubilair_Technisch_materiaal.Tapijt_groot_",
      "Tapijt (klein)" => "Rider_Meubilair_Technisch_materiaal.Tapijt_klein_",
      "Bijzettafel" => "Rider_Meubilair_Technisch_materiaal.Bijzettafel",
      "Podium" => "Rider_Meubilair_Technisch_materiaal.Podium",
      "Opmerking" => "Rider_Meubilair_Technisch_materiaal.Opmerkingen",
    ];

    return $this->extractNonEmptyfields($event, $fieldNameAndTitle);
  }

  private function convertfieldsCatering($event) {
    $fieldNameAndTitle = [
      "Koffie en thee" => "Rider_Catering.Koffie_water_en_thee",
      "Water" => "Rider_Catering.Water",
      "Fruitsap" => "Rider_Catering.Koffie_water_en_thee_fruitsap",
      "Frisdrank" => "Rider_Catering.Frisdrank",
      "Alcoholisch en non-alcoholische alternatieven" => "Rider_Catering.Water_fruitsap_bier_wijn_en_cava",
      "Enkel non-alcoholische dranken" => "Rider_Catering.Enkel_non_alcoholische_dranken",
      "Koffiefrequentie" => "Rider_Catering.Koffiecorner_bij_vergaderzalen:label",
    ];

    return $this->extractNonEmptyfields($event, $fieldNameAndTitle);
  }

  private function convertfieldsTechnischMateriaal($event) {
    $fieldNameAndTitle = [
      "Microfoon" => "Rider_Technisch_materiaal.Microfoon",
      "Aansluiting laptop" => "Rider_Technisch_materiaal.Aansluiting_laptop:label",
      "Microfoon headset" => "Rider_Technisch_materiaal.Microfoon_Headset",
      "Microfoonstatief" => "Rider_Technisch_materiaal.Microfoonstatief",
      "Stekkerdozen" => "Rider_Technisch_materiaal.Stekkerdozen",
      "Theaterspot" => "Rider_Technisch_materiaal.Theaterspot",
      "Mobiele TV" => "Rider_Technisch_materiaal.Mobiele_TV",
      "Mobiele TV - Hybride vergadering" => "Rider_Technisch_materiaal.Mobiele_TV_Hybride_vergadering",
      "Mobiele speakers" => "Rider_Technisch_materiaal.Mobiele_speakers",
    ];

    return $this->extractNonEmptyfields($event, $fieldNameAndTitle);
  }

  private function extractNonEmptyfields($event, $fieldNameAndTitle) {
    $arr = [];
    foreach ($fieldNameAndTitle as $title => $fieldName) {
      if (!empty($event[$fieldName])) {
        if ($fieldName == 'Rider_Meubilair_Technisch_materiaal.Opstelling_tekening'  || $fieldName == 'Rider_Meubilair_Technisch_materiaal.Riders_Enkel_indien_meerdere_zalen_te_gelijk') {
          $arr[$title] = $this->getFileUrl($event[$fieldName]);
        }
        else {
          $arr[$title] = $event[$fieldName];
        }
      }
    }

    return $arr;
  }

  private function isInRoomOfInterest($event): bool {
    $validRooms = [
      'De Wolken',
      'Agora 0',
      'Literair Salon',
      'Mallemunt',
      'Muntpunt Café',
      'De Spot',
      'De Grid',
      'Peristilium',
      'Zinneke',
      'Ketje',
      'Leeslamp',
      'Agora Tribune',
      'Op tafel / À table / Under the table'
    ];

    $roomsAsString = implode(', ', $event['extra_evenement_info.muntpunt_zalen:label']);
    foreach ($validRooms as $room) {
      if (str_contains($roomsAsString, $room)) {
        return TRUE;
      }
    }

    return FALSE;
  }

  private function getFileUrl($fileId) {
    $entityId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_EntityFile', $fileId, 'entity_id', 'file_id');
    [$path] = CRM_Core_BAO_File::path($fileId, $entityId);

    $fileDetails = \Civi\Api4\File::get(FALSE)
      ->addSelect('mime_type')
      ->addWhere('id', '=', $fileId)
      ->execute()
      ->first();

    return CRM_Utils_File::getFileURL($path, $fileDetails['mime_type']);
  }
}
