<h1 align="center">Magento 2 Sandbox</h1> 

<div align="center">
  <p>Vendor_Module for learning purpose</p>
  <img src="https://img.shields.io/badge/magento-2.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

## Installation

1. Create a ```Vendor/``` directory into the ```app/code/``` directory of your Magento 2 project, 
2. Copy and paste the contents of this repository directly into the ```app/code/Vendor/``` directory,
3. Run the following commands in your terminal
   ```
   bin/magento module:enable Vendor_Base
   bin/magento module:enable Vendor_Module
   bin/magento setup:upgrade
   bin/magento setup:di:compile
   ```

## License

[MIT](https://opensource.org/licenses/MIT)
