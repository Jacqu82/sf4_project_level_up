<?php

namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $markdown;
    private $cache;
    private $markdownLogger;
    private $isDebug;

    public function __construct(MarkdownInterface $markdown, AdapterInterface $cache, LoggerInterface $markdownLogger, bool $isDebug)
    {
        $this->markdown = $markdown;
        $this->cache = $cache;
        $this->markdownLogger = $markdownLogger;
        $this->isDebug = $isDebug;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'bacon') !== false) {
            $this->markdownLogger->info('They are talking about bacon again!');
        }

        if ($this->isDebug) {
            return $this->markdown->transform($source);
        }

        $item = $this->cache->getItem('markdown_' . md5($source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}
