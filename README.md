# GetContact API

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-777bb4?logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![Open Source Love svg1](https://badges.frapsoft.com/os/v1/open-source.svg?v=103)](https://github.com/restugbk/qris-interactive)

**[Un-Official]** A lightweight, Un-Official PHP Library for validating phone numbers and retrieving tags from the GetContact API. Designed to be framework-agnostic, efficient, and easy to integrate.

---

## ⚡ Get Contact Demo
An interactive demo site for contact information lookup and phone number identification services.

Live Demo: <a href="http://rfpdev.me/get-contact" target="_blank">Visit Get Contact Demo</a>

---

## 📦 Installation

Install the package via [Composer](https://getcomposer.org/):

```bash
composer require restugbk/get-contact
```

## 1. Basic Initialization

```php
use Restugbk\GetContact;

$token    = 'YOUR_TOKEN';
$finalKey = 'YOUR_FINAL_KEY';

$getContact = new GetContact($token, $finalKey);
```

## 2. Validate Number of Tags

```php
$number = '081234567890';

$response = $getContact->checkNumber($number);

if ($response['success']) {
    echo "Validated Number: " . $response['number'] . "\n";
    echo "Tags: " . implode(', ', $response['tags']) . "\n";
} else {
    echo "Error: " . $response['message'] . "\n";
}
```

## 3. Search Profile by Validated Number

```php
$number = '081234567890';

$response = $getContact->searchNumber($number);

if ($response['success']) {
    echo "Validated Number: " . $response['number'] . "\n";
    echo "Profile: " . implode(', ', $response['profile']) . "\n";
} else {
    echo "Error: " . $response['message'] . "\n";
}
```

## 📋 Data Structure Reference

The `checkNumber()` method returns an array with the following keys:

| Key | Type | Description |
| :--- | :--- | :--- |
| `success` | **Boolean** | Indicates whether the request was successful. |
| `number` | **String** | Normalized phone number (e.g., `+6281234567890`). |
| `tags` | **Array** | List of tags associated with the number. |
| `raw` | **Array** | Full raw response from the API (decrypted JSON). |

## 🔑 How to Get Token

**Requirements**: Android with ROOT-rights (or emulator).
1. Install and login into **GetContact** app.
2. Open file manager on your phone and navigate to:
    ```bash
    /data/data/app.source.getcontact/shared_prefs/GetContactSettingsPref.xml
    ```
3. Inside the file, you will find:
    ```bash
    YOUR_TOKEN = TOKEN / CHAT_TOKEN
    YOUR_FINAL_KEY = FINAL_KEY
    ```

## ⚠️ Note

If **TOKEN**, **CHAT_TOKEN**, or **FINAL_KEY** are missing.

Try updating the **GetContact** app to the latest version and log in with your account. Perform a search to trigger the CAPTCHA. This CAPTCHA cannot be bypassed using this repository due to technical limitations.
After solving the CAPTCHA and confirming that the account can fetch **Tags**, you can downgrade the app to version **6.0.0**.
Follow the steps above to resolve the issue.

📄 License
------------

This open-source software is distributed under the MIT License. See LICENSE for more information.

## 🛠 Support

If you found this project helpful, please give it a ⭐ star!

For issues and questions, please create an issue in the GitHub repository.
