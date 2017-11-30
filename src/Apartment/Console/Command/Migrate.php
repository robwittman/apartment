<?php
/**
 * Apartment
 *
 * (The MIT license)
 * Copyright (c) 2015 Rob Morgan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated * documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package    Apartment
 * @subpackage Apartment\Console
 */
namespace Apartment\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class Migrate extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('migrate')
             ->setDescription('Migrate the database')
             ->addArgument('type', InputArgument::OPTIONAL, 'Type of migrations run')
             ->addOption('--target', '-t', InputOption::VALUE_REQUIRED, 'The version number to migrate to')
             ->addOption('--date', '-d', InputOption::VALUE_REQUIRED, 'The date to migrate to')
             ->addOption('--dry-run', '-x', InputOption::VALUE_NONE, 'Dump query to standard output instead of executing it')
             ->setHelp(
                 <<<EOT
The <info>migrate</info> command runs all available migrations, optionally up to a specific version

<info>Apartment migrate </info>
<info>Apartment migrate  -t 20110103081132</info>
<info>Apartment migrate  -d 20110103</info>
<info>Apartment migrate  -v</info>

EOT
             );
    }

    /**
     * Migrate the database. We need to find out which subsection of
     * databases we're running on [landlord or tenants]. Then, run
     * all necessary migrations. Run landlord inline, run all tenants in parallel
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int integer 0 on success, or an error code.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);

        $type = $input->getArgument('type');
        $version = $input->getOption('target');
        $date = $input->getOption('date');
        $envOptions = $this->getConfig()->getEnvironment($environment);

        switch($type) {
            case 'landlord':
                $this->migrateLandlord($input, $output);
                break;
            case 'tenant':
                $this->migrateTenants($input, $output);
                break;
            default:
                $this->migrateLandlord($input, $output);
                $this->migrateTenants($input, $output);
        }
        
        if (isset($envOptions['adapter'])) {
            $output->writeln('<info>using adapter</info> ' . $envOptions['adapter']);
        }

        if (isset($envOptions['wrapper'])) {
            $output->writeln('<info>using wrapper</info> ' . $envOptions['wrapper']);
        }

        if (isset($envOptions['name'])) {
            $output->writeln('<info>using database</info> ' . $envOptions['name']);
        } else {
            $output->writeln('<error>Could not determine database name! Please specify a database name in your config file.</error>');

            return 1;
        }

        if (isset($envOptions['table_prefix'])) {
            $output->writeln('<info>using table prefix</info> ' . $envOptions['table_prefix']);
        }
        if (isset($envOptions['table_suffix'])) {
            $output->writeln('<info>using table suffix</info> ' . $envOptions['table_suffix']);
        }

        // run the migrations
        $start = microtime(true);
        if ($date !== null) {
            $this->getManager()->migrateToDateTime($environment, new \DateTime($date));
        } else {
            $this->getManager()->migrate($environment, $version);
        }
        $end = microtime(true);

        $output->writeln('');
        $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');

        return 0;
    }

    protected function migrateLandlord(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getOption('target');
        $date = $input->getOption('date');

    }

    protected function migrateTenants(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getOption('target');
        $date = $input->getOption('date');

    }

    public function migrate($environment)
    {

    }
}
