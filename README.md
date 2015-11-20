# ZenstruckBackupBundle

[![Build Status](http://img.shields.io/travis/kbond/ZenstruckBackupBundle.svg?style=flat-square)](https://travis-ci.org/kbond/ZenstruckBackupBundle)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/kbond/ZenstruckBackupBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/ZenstruckBackupBundle/)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/kbond/ZenstruckBackupBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/ZenstruckBackupBundle/)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/537700be-cb48-4bfd-9b77-356b1ad77cc3.svg?style=flat-square)](https://insight.sensiolabs.com/projects/537700be-cb48-4bfd-9b77-356b1ad77cc3)
[![StyleCI](https://styleci.io/repos/18815669/shield)](https://styleci.io/repos/18815669)
[![Latest Stable Version](http://img.shields.io/packagist/v/zenstruck/backup-bundle.svg?style=flat-square)](https://packagist.org/packages/zenstruck/backup-bundle)
[![License](http://img.shields.io/packagist/l/zenstruck/backup-bundle.svg?style=flat-square)](https://packagist.org/packages/zenstruck/backup-bundle)

This bundle allows creating and managing backups in a Symfony application. It is a wrapper for
[zenstruck/backup](https://github.com/kbond/php-backup).

## Installation

Require this bundle with composer:

    composer require zenstruck/backup-bundle

Then enable it in your kernel:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        //...
        new Zenstruck\BackupBundle\ZenstruckBackupBundle(),
        //...
    );
}
```

## Configuration

In your `config.yml` add at least one source, namer, processor, and destination as well as a profile.

Example:

```yaml
zenstruck_backup:
    sources:
        database:
            mysqldump:
                database: my_database
        files:
            rsync:
                source: "%kernel.root_dir%/../web/files"
                additional_options:
                    - --exclude=_cache/
    namers:
        daily:
            timestamp:
                format: d
                prefix: mysite-
        snapshot:
            timestamp:
                prefix: mysite-
    processors:
        zip: { zip: ~ }
    destinations:
        s3:
            s3cmd:
                bucket: "s3://foobar/backups"
    profiles:
        daily:
            scratch_dir: "%kernel.root_dir%/cache/backup"
            sources: [database, files]
            namer: daily
            processor: zip
            destinations: [s3]
```

## Commands

### Run Backup Command

```
Usage:
 zenstruck:backup:run [--clear] [<profile>]

Arguments:
 profile  The backup profile to run (leave blank for listing)

Options:
 --clear  Set this flag to clear scratch directory before backup
```

**NOTES**:

1. Add `-vv` to see the log.
2. For long running backups, it may be required to increase the `memory_limit` in your `app/console`/`bin/console`.
3. Running the command without a profile will list available profiles.

Examples (with the above configuration):

* Create a backup at: `s3://foobar/backups/mysite-{day-of-month}`

        app/console zenstruck:backup:run daily

* Create a backup at: `s3://foobar/backups/mysite-{YYYYMMDDHHMMSS}`

        app/console zenstruck:backup:run snapshot

### List Existing Backups

```
Usage:
  zenstruck:backup:list [<profile>]

Arguments:
  profile  The backup profile to list backups for (leave blank for listing)
```

**NOTE**: Running the command without a profile will list available profiles.

## Full Default Config

```yaml
zenstruck_backup:
    namers:

        # Prototype
        name:
            simple:
                name:                 backup
            timestamp:
                format:               YmdHis
                prefix:               ''
                timezone:             ~
    processors:

        # Prototype
        name:
            zip:
                options:              '-r'
                timeout:              300
            gzip:
                options:              '-czvf'
                timeout:              300
    sources:

        # Prototype
        name:
            mysqldump:
                database:             ~ # Required
                host:                 ~
                user:                 root
                password:             null
                ssh_host:             null
                ssh_user:             null
                ssh_port:             22
                timeout:              300
            rsync:
                source:               ~ # Required
                timeout:              300
                additional_options:   []
                default_options:

                    # Defaults:
                    - -acrv
                    - --force
                    - --delete
                    - --progress
                    - --delete-excluded
    destinations:

        # Prototype
        name:
            stream:
                directory:            ~ # Required
            flysystem:
                filesystem_service:   ~ # Required
            s3cmd:
                bucket:               ~ # Required, Example: s3://foobar/backups
                timeout:              300
    profiles:

        # Prototype
        name:
            scratch_dir:          '%kernel.cache_dir%/backup'
            sources:              [] # Required, can be a string
            namer:                ~  # Required
            processor:            ~  # Required
            destinations:         [] # Required, can be a string
```
