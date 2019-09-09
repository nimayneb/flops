FLOPS - Fluent Local PHile system API
=====================================

FLOPS is a API only for the Local File system with a fluent but verbosity (no abbreviations) interface support.

Key features:

- No dependencies (excepts POSIX extension)
- No regular expressions
- No use of SPL (Standard PHP Library)
- Proven and simplest patterns (see PHP documentation)

Example of use:

    use JayBeeR\Flops\LocalFileSystem;
    
    $localBinaries = LocalFileSystem::get('/usr/local/bin/');
    $composerFile = LocalFileSystem::get('composer.phar');
    $composerBinary = $composerFile->copyTo($localBinaries, '')->omitExtension();
    $composerBinary->setPermission(function(Permissions $permissions) {
        $permissions->setExecutionAccessRightsForAll();
    });


Table of contents
-----------------

1. [Configuration](Documentation/Configuration..md)
1. [File information](Documentation/FileInformation.md)
1. [Directory information](Documentation/DirectoryInformation.md)
1. [Symbolic link information](Documentation/SymbolicLinkInformation.md)
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