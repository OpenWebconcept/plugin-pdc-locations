<?php

namespace OWC\PDC\Locations\Traits;

use WP_Post;

trait Attachment
{
    /**
     * Get meta data of an attachment.
     */
    public function getAttachmentMeta(int $id): array
    {
        $meta = wp_get_attachment_metadata($id, false);

        if (! $meta) {
            return [];
        }

        if (empty($meta['sizes'])) {
            return [];
        }

        foreach (array_keys($meta['sizes']) as $size) {
            $src = wp_get_attachment_image_src($id, $size);
            $meta['sizes'][$size]['url'] = (! $src) ? '' : $src[0];
        }

        unset($meta['image_meta']);

        return $meta;
    }

    /**
     * Gets the featured image of a post.
     */
    public function getFeaturedImage(WP_Post $post): array
    {
        if (! has_post_thumbnail($post->ID)) {
            return [];
        }

        $id = (int) get_post_thumbnail_id($post->ID);

        $currentBlogID = get_current_blog_id();

        if ($this->switchToCentralMediaSite($currentBlogID)) {
            \switch_to_blog($this->getCentralMediaSiteID()); // Switch to central media site where the image is stored.
        }

        $attachment = get_post($id);

        if (! $attachment instanceof WP_Post) {
            return [];
        }

        $imageSize = 'large';

        $result = [];
        $result['title'] = $attachment->post_title;
        $result['description'] = $attachment->post_content;
        $result['caption'] = $attachment->post_excerpt;
        $result['alt'] = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        $meta = $this->getAttachmentMeta($id);

        $result['rendered'] = wp_get_attachment_image($id, $imageSize);
        $result['sizes'] = wp_get_attachment_image_sizes($id, $imageSize, $meta);
        $result['srcset'] = wp_get_attachment_image_srcset($id, $imageSize, $meta);
        $result['meta'] = $meta;

        if ($this->switchToCentralMediaSite($currentBlogID)) {
            \restore_current_blog(); // After switching return to initial site.
        }

        return $result;
    }

    /**
     * Check if the plugin is active and the current blog is not the central media site.
     */
    protected function switchToCentralMediaSite(int $currentBlogID): bool
    {
        return $this->isPluginActive('network-media-library/network-media-library.php') && $currentBlogID !== $this->getCentralMediaSiteID();
    }

    protected function getCentralMediaSiteID(): int
    {
        $siteID = $_ENV['NETWORK_MEDIA_LIBRARY_SITE_ID'] ?? 2;

        return (int) $siteID;
    }
}
