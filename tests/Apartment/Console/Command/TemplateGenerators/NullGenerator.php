<?php
namespace Test\Apartment\Console\Command\TemplateGenerators;

use Apartment\Migration\AbstractTemplateCreation;

class NullGenerator extends AbstractTemplateCreation
{
    /**
     * Get the migration template.
     *
     * This will be the content that Apartment will amend to generate the migration file.
     *
     * @return string The content of the template for Apartment to amend.
     */
    public function getMigrationTemplate()
    {
        // TODO: Implement getMigrationTemplate() method.
    }

    /**
     * Post Migration Creation.
     *
     * Once the migration file has been created, this method will be called, allowing any additional
     * processing, specific to the template to be performed.
     *
     * @param string $migrationFilename The name of the newly created migration.
     * @param string $className The class name.
     * @param string $baseClassName The name of the base class.
     * @return void
     */
    public function postMigrationCreation($migrationFilename, $className, $baseClassName)
    {
        // TODO: Implement postMigrationCreation() method.
    }
}
