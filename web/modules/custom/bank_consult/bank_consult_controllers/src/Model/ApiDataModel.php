<?php

namespace Drupal\bank_consult_controllers\Model;
class ApiDataModel {
  // Define properties to match the JSON data structure
  public $id;
  public $name;
  public $email;
  public $age;
  public $address;

  // Constructor to initialize the object with API data
  public function __construct($jsonData) {
    // Assuming $jsonData is a valid JSON string
    $data = json_decode($jsonData, true);

    // Check if decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
      // Populate properties with data from JSON
      $this->id = $data['id'] ?? null;
      $this->name = $data['name'] ?? null;
      $this->email = $data['email'] ?? null;
      $this->age = $data['age'] ?? null;
      $this->address = $data['address'] ?? null;
    } else {
      // Handle JSON decoding error
      throw new \Exception('Error decoding JSON data');
    }
  }
}
