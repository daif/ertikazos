# Change Log

## 1.3.0 - 2017-04-25
### Added
- Add `up` and `down` option for migrate and seed commands.
- Add `setup` function to migration and seed file to prepare the data array.

### Changed
- Rollback the migration or the seed using `down` option.

### Removed
- Remove rollback command.
- Remove version_by_name function.

## 1.2.0 - 2017-03-23
### Added
- Implement ErtikazOS store.
- Add new commands creator token, creator push, creator pack and creator pull.
- New command base class.
- Add application/packages folder.
- New command template.

### Changed
- Creator command improved.

## 1.0.4 - 2017-01-12
### Changed
- Upgrade CodeIgniter to 3.2.0-dev.

### Fixed
- Fix Session id filed length in session table. 

## 1.0.3 - 2016-10-18
### Changed
- Change make_input function to return the input without label.
make_label function can used to print the input label.

## 1.0.2 - 2016-10-10
### Changed
- Use LDAP protocol version 3.

## 1.0.1 - 2016-10-08
### Changed
- Use config/autoload.php to load needed files.
- Update gitignore file.

### Removed
- Remove unused $models variable from ER_Controller.
- Remove unneeded files.

## 1.0.0 - 2016-10-02
### Added
- Initial Commit.
