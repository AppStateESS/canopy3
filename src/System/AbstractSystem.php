<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\System;

class AbstractSystem
{

    /**
     * @var array
     */
    //protected array $authors;

    /**
     * A description of the dashboard's or plugin's functionality.
     * @var string
     */
    protected string $description;

    /**
     * The system path. Likely the system's name.
     * @var string
     */
    protected string $directory;

    /**
     * Home page for information about the system.
     * @var string
     */
    protected string $homepage;

    /**
     * License type.
     * @var string
     */
    protected string $license;

    /**
     * The system's distribution name.
     * @var string
     */
    protected string $name;

    /**
     * The autoloaded namespace.
     * @var string
     */
    protected string $namespace;

    /**
     * URL to respository
     * @var string
     */
    protected string $repository;

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }



    /**
     * URL to respository release API
     * @var string
     */
    protected string $releaseAPI;

    /**
     * Speaking name of the system
     * @var string
     */
    protected string $title;

    /**
     * The system type: canopy3-dashboard or canopy3-plugin
     */
    protected string $type;

    /**
     * The system's current installed version
     */
    protected string $version;

    /**
     * Get the value of authors
     *
     * @return  array
     */
    public function getAuthors()
    {
        return $this->authors;
    }
    /**
     * Get a description of the dashboard's or plugin's functionality.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the system path. Likely the system's name.
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get home page for information about the system.
     */
    public function getHomepage()
    {
        return $this->homepage;
    }



    /**
     * Get license type.
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Get the system's distribution name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the autoloaded namespace.
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get the system type: canopy3-dashboard or canopy3-plugin
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the system's current installed version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set a description of the dashboard's or plugin's functionality.
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the system path. Likely the system's name.
     *
     * @return  self
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }


    /**
     * Set home page for information about the system.
     *
     * @return  self
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * Set license type.
     *
     * @return  self
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }
    /**
     * Set the system's distribution name.
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the autoloaded namespace.
     *
     * @return  self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $repository
     */
    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * Set the system type: canopy3-dashboard or canopy3-plugin
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the system's current installed version
     *
     * @return  self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }



    /**
     * Set the value of authors
     *
     * @param  array  $authors
     *
     * @return  self
     */
    public function setAuthors(array $authors)
    {
        $this->authors = $authors;

        return $this;
    }
}
