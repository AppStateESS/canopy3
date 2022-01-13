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

class AbstractSystem extends \Canopy3\AbstractConstruct
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
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * Get a description of the dashboard's or plugin's functionality.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the system path. Likely the system's name.
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Get home page for information about the system.
     * @return string
     */
    public function getHomepage(): string
    {
        return $this->homepage;
    }

    /**
     * Get license type.
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * Get the system's distribution name.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the autoloaded namespace.
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getReleaseAPI(): string
    {
        return $this->releaseAPI;
    }

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * Verbose system name. If name is "c3-widget", title would be
     * "Canopy 3 Widget Creator"
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the system type: canopy3-dashboard or canopy3-plugin
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the system's current installed version
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    public function isDashboard()
    {
        return $this->type === 'canopy3-dashboard';
    }

    public function isPlugin()
    {
        return $this->type === 'canopy3-plugin';
    }

    public function isTheme()
    {
        return $this->type === 'canopy3-theme';
    }

    /**
     * Set a description of the dashboard's or plugin's functionality.
     *
     * @return self
     */
    public function setDescription($description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the system path. Likely the system's name.
     *
     * @return self
     */
    public function setDirectory($directory): self
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * Set home page for information about the system.
     *
     * @return self
     */
    public function setHomepage($homepage): self
    {
        $this->homepage = $homepage;
        return $this;
    }

    /**
     * Set license type.
     *
     * @return self
     */
    public function setLicense($license): self
    {
        $this->license = $license;
        return $this;
    }

    /**
     * Set the system's distribution name.
     *
     * @return self
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the autoloaded namespace.
     *
     * @return self
     */
    public function setNamespace($namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param string $releaseAPI
     * @return self
     */
    public function setReleaseAPI(string $releaseAPI): self
    {
        $this->releaseAPI = $releaseAPI;
        return $this;
    }

    /**
     * @param string $repository
     * @return self
     */
    public function setRepository(string $repository): self
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the system type: canopy3-dashboard or canopy3-plugin
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the system's current installed version
     * @param string $version
     * @return self
     */
    public function setVersion($version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Set the value of authors
     *
     * @param array $authors
     * @return self
     */
    public function setAuthors(array $authors): self
    {
        $this->authors = $authors;
        return $this;
    }

}
