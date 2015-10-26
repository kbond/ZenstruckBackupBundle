# ZenstruckBackupBundle

[![Build Status](https://travis-ci.org/kbond/ZenstruckBackupBundle.png?branch=master)](https://travis-ci.org/kbond/ZenstruckBackupBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kbond/ZenstruckBackupBundle/badges/quality-score.png?s=6eb648598aebc72e7b07c8925c19421f7ad1548b)](https://scrutinizer-ci.com/g/kbond/ZenstruckBackupBundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/kbond/ZenstruckBackupBundle/badges/coverage.png?s=cf342c59af54f9bf00b985c04845d506d41bba9e)](https://scrutinizer-ci.com/g/kbond/ZenstruckBackupBundle/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/537700be-cb48-4bfd-9b77-356b1ad77cc3/mini.png)](https://insight.sensiolabs.com/projects/537700be-cb48-4bfd-9b77-356b1ad77cc3)
[![StyleCI](https://styleci.io/repos/18815669/shield)](https://styleci.io/repos/18815669)
[![Latest Stable Version](https://poser.pugx.org/zenstruck/backup-bundle/v/stable.png)](https://packagist.org/packages/zenstruck/backup-bundle)
[![License](https://poser.pugx.org/zenstruck/backup-bundle/license.png)](https://packagist.org/packages/zenstruck/backup-bundle)

This bundle acts as a wrapper for backing up your Symfony application. A backup "profile" has 3 parts:

1. **Source**: What to backup (ie database/files). This step fetches files and copies them to a "scratch"
directory. These files are typically persisted between backups (improves rsync performance) but can be
cleared with the command's `--clear` flag.
2. **Processor**: Convert to a single file (ie zip/tar.gz).  This step uses a **Namer** to name the file.
3. **Destination**: Where to send the backup (ie filesystem/S3).

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
            destination: s3
```

## Backup Command

```
Usage:
 zenstruck:backup [--clear] profile

Arguments:
 profile  The backup profile to run

Options:
 --clear  Set this flag to clear scratch directory before backup
```

**NOTE**: add `-vv` to see the log.

Examples (with the above configuration):

* Create a backup at: `s3://foobar/backups/mysite-{day-of-month}`

        app/console zenstruck:backup daily

* Create a backup at: `s3://foobar/backups/mysite-{YYYYMMDDHHMMSS}`

        app/console zenstruck:backup snapshot

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
    processors:

        # Prototype
        name:
            zip:
                options:              '-r'
            gzip:
                options:              '-czvf'
    sources:

        # Prototype
        name:
            mysqldump:
                database:             ~ # Required
                user:                 root
                password:             null
                ssh_host:             null
                ssh_user:             null
                ssh_port:             22
            rsync:
                source:               ~ # Required
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
            s3cmd:
                bucket:               ~ # Required, Example: s3://foobar/backups
                timeout:              300
    profiles:

        # Prototype
        name:
            scratch_dir:          '%kernel.cache_dir%/backup'
            sources:              [] # Required
            namer:                ~ # Required
            processor:            ~ # Required
            destination:          ~ # Required
```
