<?php
/**
 * This file is part of PhergieUrl.
 *
 ** (c) 2014 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phergie\Irc\Plugin\React\Url\Mime;

use Phergie\Irc\Plugin\React\Url\UrlInterface;

class Image implements MimeInterface
{
    const MIME = 'image/';
    const LMIME = 6;

    /**
     * Return whether this mimetype is supported by this handler.
     *
     * @param string $mimeType The mimetype to check.
     *
     * @return boolean
     */
    public function matches($mimeType)
    {
        return (substr($mimeType, 0, static::LMIME) == static::MIME);
    }

    /**
     * Extract all possible useful information from the given url.
     *
     * @param array        $replacements Message replacements.
     * @param UrlInterface $url          URL to extract data from.
     *
     * @return array
     */
    public function extract(array $replacements, UrlInterface $url)
    {
        $size = @\getimagesize('data://application/octet-stream;base64,'  . base64_encode($url->getBody()));
        if ($size) {
            $replacements = $this->extractIntoReplacements($replacements, $size);
        }

        return $replacements;
    }

    /**
     * Extract data from $size into $replacements.
     *
     * @param array $replacements Extract data into this.
     * @param array $size         Extract data from this.
     *
     * @return array
     */
    protected function extractIntoReplacements(array $replacements, array $size)
    {
        $replacements['%image-width%'] = $size[0];
        $replacements['%image-height%'] = $size[1];
        if (isset($size['channels'])) {
            $replacements['%image-channels%'] = $size['channels'];
        }
        if (isset($size['mime'])) {
            $replacements['%image-mime%'] = $replacements['%composed-title%'] = $size['mime'];
        }

        return $replacements;
    }
}
