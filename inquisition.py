#!/usr/bin/python3

"""
Inquisition.py

APP: Inquisition
DESC: The main running container for the Inquisition SIEM platform
CREATION_DATE: 2017-04-07

"""

# MODULES
# | Native
import argparse
import configparser
from os import path

# | Third-Party

# | Custom
from lib.anatomize.Anatomize import Anatomize

# METADATA
__author__ = 'Joshua Carlson-Purcell'
__copyright__ = 'Copyright 2017, CarlsoNet'
__license__ = 'MIT'
__version__ = '1.0.0-alpha'
__maintainer__ = 'Joshua Carlson-Purcell'
__email__ = 'jcarlson@carlso.net'
__status__ = 'Development'


# FUNCTIONS
def parseCliArgs():
    """
    Parse CLI arguments
    
    :return: post-parsing ArgumentParser() obj
    """

    # set CLI arguments
    cliParser = argparse.ArgumentParser(description='An advanced and versatile open-source SIEM platform')
    cliParser.add_argument('-c', '--config-file', help='Configuration file to be used', default='conf/main.cfg')
    cliParser.add_argument('-k', '--config-check', action='store_true',
                           help='Try running Inquisition up to after the configs are read in and parsed', default=False)
    cliParser.add_argument('-t', '--test-run', action='store_true',
                           help='Run Inquisition in test mode (read-only, debug-level logs, single log processes)',
                           default=False)

    # read in args
    return cliParser.parse_args()

def parseConfigFile(configFile):
    """
    Parse App Configuration from File
    
    :param configFile: filename of configuration file to read in
    :return: configparser.ConfigParser()
    """

    # create CP object
    cfg = configparser.ConfigParser()

    # check if config file is readable
    if path.isfile(configFile):
        # ingest and return configuration
        cfg.read(configFile)

        return (cfg)
    else:
        print('[CRIT] config file not found :: [', configFile, ']')

        exit(1)

def generateCfg():
    """
    Generate master config set from CLI and config file values

    :return: dict
    """

    # read in CLI params
    cliArgs = parseCliArgs()

    # read in config file
    cfg = None
    try:
        cfg = parseConfigFile(cliArgs.config_file)
    except ImportError as err:
        print('[CRIT] configparser module not available\n\nDETAILS:\n', err)

        exit(1)
    except configparser.Error as err:
        print('[CRIT] could not parse config file :: [', cliArgs.config_file, ']\n\nDETAILS:\n', err.message)

        exit(1)

    # convert cli args format to config file format and combine them
    # NOTE: for some reason, all values must be strings with the configparser library
    cfg.add_section('cli')
    cfg['cli']['config_check'] = str(cliArgs.config_check)
    cfg['cli']['test_run'] = str(cliArgs.test_run)

    return cfg

def main():
    """
    Main runtime module

    :return: void
    """

    # read in config file
    cfg = generateCfg()

    # start Anatomize.py instance
    anatomize = Anatomize(cfg)
    anatomize.lgr.debug('anatomizer initialization [ SUCCESSFUL ]')

    # start polling process
    if not cfg.getboolean('cli', 'config_check'):
        anatomize.startAnatomizer()

        # start Destiny.py instance
        # TODO
    else:
        msg = 'configuration check is SUCCESSFUL, exiting...'
        print('[INFO] ' + msg)
        anatomize.lgr.info(msg)


if __name__ == '__main__':
    main()