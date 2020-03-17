<?php declare(strict_types=1);

/**
 * @param mixed $object
 * @param bool  $displayContent
 *
 * @return void
 */
function debug($object, bool $displayContent = false): void
{
    if (!\is_object($object) || $displayContent) {
        \var_dump($object);
    } else {
        \var_dump(\get_class($object));
    }

    try {
        ob_flush();
        flush();
    } catch (\Throwable $e) {
        // Nothing to flush
    }
}

/**
 * @return void
 */
function trace(): void
{
    debug(
        (new \Exception())->getTraceAsString()
    );
}

