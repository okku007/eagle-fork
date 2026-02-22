[![Open Source Love png1](https://badges.frapsoft.com/os/v1/open-source.png?v=103)](https://github.com/ellerbrock/open-source-badges/)
[![GPLv3 license](https://img.shields.io/badge/License-GPLv3-blue.svg)](http://perso.crans.org/besson/LICENSE.html)

# Eagle Crypto Suite (SYS.OP.VER.2.0)

:star: Star this repository — it helps!

**Eagle** is a comprehensive, open-source cryptographic suite providing an array of string encoders/decoders, standard hashes, password hashing algorithms, OpenSSL encryption/decryption, and modern Libsodium secure cryptography. Designed with a sleek, cyberpunk-inspired interface, it runs completely without a database backend, keeping the application lightning fast, stateless, and minimal.

## Features

* **Comprehensive Crypto Toolkit**: Bindings for over dozens of algorithms including `base64`, `hex`, PHP native hashes, `bcrypt`/`argon2i(d)` password hashing, OpenSSL (`AES`/`ChaCha20`), and `Libsodium` (Generic Hash & Secretbox).
* **Core Manager Class**: All cryptographic operations are securely managed by a single, strictly typed `CryptoManager` class ensuring safety and modularity.
* **Cyberpunk UI/UX**: Redesigned front-end utilizing TailwindCSS and Alpine.js for a responsive, interactive, and visually striking accordion-based user experience.
* **Composer Native**: Built using modern PHP standards, leveraging Composer for autoloading and dependency management (`vlucas/phpdotenv`).
* **Environment Controlled**: Easy configuration via `.env` files.

## Requirements

* **PHP** >= 8.0
* **Composer**
* **PHP Extensions**: 
  * `openssl` (Required for standard symmetric encryption)
  * `sodium` (Highly recommended for modern cryptography modules)
  * `mbstring`, `ctype`

## Installation

1. **Clone the repository** to your web server or local environment:
   ```bash
   git clone https://gitlab.com/daksh7011/eagle.git
   cd eagle
   ```

2. **Install dependencies** using Composer:
   ```bash
   composer install
   ```

3. **Configure Environment Variables**:
   Create a `.env` file in the root directory and define your application name and other configuration values:
   ```env
   APP_NAME="Project Eagle"
   ```

4. **Web Server Configuration**:
   Point your web server's document root to the `public/` directory (where `index.php` resides) to ensure security and proper routing. 
   
   *Alternatively, if running locally, you can use the built-in PHP server:*
   ```bash
   cd public/
   php -S localhost:8000
   ```

## Contribution Guide
Please take a look at the [contributing](CONTRIBUTING.md) guidelines if you're interested in helping!

## License
The Eagle Project is licensed under the terms of the GPL Open Source license version 3 and is available for free.
You should have received a copy of the GNU General Public License along with this program. 
If not, see [GPL-v3](https://www.gnu.org/licenses/gpl-3.0.html)

## Links
[Website](https://gitlab.com/daksh7011/eagle) | [Issue Tracker](https://gitlab.com/daksh7011/eagle/issues)

**Thanks to:**
* [Duck Duck Go](https://duckduckgo.com/) for not tracking me while trying to search what went wrong with my code.
* [Stack Overflow](https://stackoverflow.com/) for providing a platform to help fellow developers.
* [Siddhi Agrawal](https://gitlab.com/echo-siddhi) for continuous assistance.