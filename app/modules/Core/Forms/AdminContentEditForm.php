<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Forms;

use Engine\Forms\Element\Datalist;
use Module\Core\Models\Category;
use Module\Core\Models\ViewLevel;
use Engine\Forms\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class AdminContentEditForm
 * @package Module\Core\Forms
 */
class AdminContentEditForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        parent::initialize();
        
        // Id
        $id = new Hidden('id');
        $id->setFilters('int');
        $this->add($id);

        // Title
        $title = new Text('title', [
            'class' => 'form-control'
        ]);
        $title->setLabel($this->t->_('Title'));
        $title->setFilters('string');
        $title->addValidator(
            new PresenceOf([
                'title' => $this->t->_('%field% is required', ['field' => $this->t->_('Title')])
            ])
        );
        $this->add($title);

        // Alias
        $alias = new Text('alias', [
            'class' => 'form-control'
        ]);
        $alias->setLabel($this->t->_('Alias'));
        $alias->setFilters('alias');
        $this->add($alias);

        // Custom url
        $customUrl = new Text('customUrl', [
            'class' => 'form-control'
        ]);
        $customUrl->setLabel($this->t->_('Custom Url'));
        $customUrl->setFilters('url');
        $this->add($customUrl);

        // Featured intro image
        $introImage = new Text('introImage', [
            'class' => 'form-control',
        ]);
        $introImage->setLabel($this->t->_('Featured Intro Image'));
        $introImage->setFilters('string');
        $this->add($introImage);

        // Intro Text
        $introText = new TextArea('introtext', [
            'class' => 'form-control'
        ]);
        $introText->setFilters(['escapeHtml', 'string']);
        $this->add($introText);

        // Featured full text image
        $fullTextImage = new Text('fulltextImage', [
            'class' => 'form-control',
        ]);
        $fullTextImage->setLabel($this->t->_('Featured Full text Image'));
        $fullTextImage->setFilters('string');
        $this->add($fullTextImage);

        // Full Text
        $fullText = new TextArea('fulltext', [
            'class' => 'form-control'
        ]);
        $fullText->setFilters(['escapeHtml', 'string']);
        $this->add($fullText);

        // Categories
        $category = new Select('category',
            Category::find(),
            ['using' => ['id', 'title'], 'class' => 'form-control']
        );
        $category->setLabel($this->t->_('Category'));
        $category->setFilters('int');
        $category->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Category')])
            ])
        );
        $this->add($category);

        // Featured
        $featured = new Select('featured', [0 => 'No', 1 => 'Yes'], ['class' => 'form-control']);
        $featured->setLabel($this->t->_('Featured'));
        $featured->setFilters('int');
        $this->add($featured);

        // Status
        $status = new Select('status',
            $this->helper->getArticleStatuses(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $status->setLabel($this->t->_('Status'));
        $status->setFilters('int');
        $status->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Status')])
            ])
        );
        $this->add($status);

        // User view level
        $role = new Select('view_level',
            ViewLevel::find(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $role->setLabel($this->t->_('View Level'));
        $role->setFilters('int');
        $role->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('View Level')])
            ])
        );
        $this->add($role);

        $publishUp = new Text('publish_up', [
            'class' => 'form-control'
        ]);
        $publishUp->setLabel($this->t->_('Publish date'));
        $publishUp->setFilters('string');
        $publishUp->setAttribute('timestamp', true);
        $publishUp->addValidator(
            new PresenceOf([
                'message' => $this->t->_('%field% is required', ['field' => $this->t->_('Publish date')])
            ])
        );
        $this->add($publishUp);

        $publishDown = new Text('publish_down', [
            'class' => 'form-control'
        ]);
        $publishDown->setLabel($this->t->_('End date'));
        $publishDown->setFilters('string');
        $publishDown->setAttribute('timestamp', true);
        $this->add($publishDown);

        $attrLayout = new Datalist('attrLayout', [],
            ['placeholder' => 'Default', 'class' => 'form-control']
        );
        $attrLayout->setLabel($this->t->_('Layout'));
        $attrLayout->setFilters('string');
        $this->add($attrLayout);


        // ---- SEO ----
        // Meta Title
        $metaTitle = new Text('metaTitle', [
            'class' => 'form-control'
        ]);
        $metaTitle->setLabel($this->t->_('Meta Title'));
        $metaTitle->setFilters('string');
        $this->add($metaTitle);

        // Meta Keywords
        $metaKeyword = new Text('metaKeywords', [
            'class' => 'form-control'
        ]);
        $metaKeyword->setLabel($this->t->_('Meta Keywords'));
        $metaKeyword->setFilters('string');
        $this->add($metaKeyword);

        // Meta Description
        $metaDescription = new TextArea('metaDescription', [
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control'
        ]);
        $metaDescription->setLabel($this->t->_('Meta Description'));
        $metaDescription->setFilters('string');
        $this->add($metaDescription);
    }

    public function bind(array $data, $entity, $whitelist = null)
    {
        $meta = [];
        $attributes = [];
        foreach ($data as $key => $val) {
            if (substr( $key, 0, 4 ) === "meta") {
                $meta[$key] = $val;
                unset($data[$key]);
            } else if (substr( $key, 0, 4 ) === "attr") {
                $attributes[$key] = $val;
                unset($data[$key]);
            }
        }
        $entity->setMetadata(json_encode($meta));
        $entity->setAttributes(json_encode($attributes));

        $images = ['introImage' => $data['introImage'], 'fulltextImage' => $data['fulltextImage']];
        $entity->setImages(json_encode($images));

        if (!isset($data['featured'])) {
            $entity->setFeatured(0);
        }

        parent::bind($data, $entity, $whitelist);
    }

    public function setEntity($entity)
    {
        $this->mapEntity($entity->getMetadataArray());
        $this->mapEntity($entity->getImagesArray());
        $this->mapEntity($entity->getAttributesArray());
        parent::setEntity($entity);
    }
}