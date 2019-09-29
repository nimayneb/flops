FLOPS - Fluent Local PHile system API
=====================================

FLOPS is a API only for the Local File system with a fluent but verbosity (no abbreviations) interface support.

Key features:

- No dependencies (excepts POSIX extension)
- No regular expressions
- No use of SPL (Standard PHP Library)
- Proven and simplest patterns (see PHP documentation)

Use PHP internals for filesystem:

`chgrp` `chmod` `clearstatcache` `copy` `disk_​free_​space` `disk_​total_​space` `fclose` `feof` `fgetc` `fgets`
`file_​exists` `file_​get_​contents` `file_​put_​contents` `fileatime` `filectime` `filegroup` `filemtime` `fileowner`
`fileperms` `filesize` `fopen` `fread` `fseek` `ftell` `ftruncate` `fwrite` `is_​dir` `is_​executable` `is_​file`
`is_​link` `is_​readable` `is_​writable` `mkdir` `pathinfo` `readlink` `realpath` `rename` `rmdir` `symlink` `touch` `unlink`

Example of use:

    use JayBeeR\Flops\LocalFileSystem;
    
    $localBinaries = LocalFileSystem::get('/usr/local/bin/');
    $composerFile = LocalFileSystem::get('composer.phar');
    $composerBinary = $composerFile->copyTo($localBinaries)->omitExtension();
    $composerBinary->setPermission(function(Permissions $permissions) {
        $permissions->setExecutionAccessRightsForAll();
    });


Table of contents
-----------------

1. [Configuration](Documentation/Configuration..md)
1. [File information](Documentation/FileInformation.md)
1. [Directory information](Documentation/DirectoryInformation.md)
1. [Symbolic link information](Documentation/SymbolicLinkInformation.md)
1. [File resources](Documentation/FileResources.md)
1. [File encoding](Documentation/FileEncoding.md)
1. [File modes](Documentation/FileModes.md)
1. [Permissions](Documentation/Permissions.md)
1. [Root line](Documentation/RootLine.md)
1. [Write content](Documentation/WriteContent.md)
1. [Exceptions](Documentation/Exceptions.md)

Wishlist
--------

- Tests!
- more Documentations
- Exceptions give context help
- more Semantic Exceptions (and Refactoring)
- Execution process (STDIN, STDOUT, STDERR)