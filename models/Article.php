<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $content
 * @property string|null $date
 * @property string|null $image
 * @property int|null $viewed
 * @property int|null $user_id
 * @property int|null $status
 * @property int|null $category_id
 *
 * @property ArticleTag[] $articleTags
 * @property Comment[] $comments
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['title', 'description', 'content'], 'string'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return ($this->image) ? '/yiiblog/web/uploads/' . $this->image : '/yiiblog/web/default-image.png';
    }

    /**
     * @param $filename
     * @return bool
     */
    public function saveImage($filename)
    {
        $this->image = $filename;
        return $this->save(false);
    }

    private function deleteImage()
    {
        $imageUpload = new ImageUpload();
        $imageUpload->deleteCurrentImage($this->image);
    }

    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete();
    }

    // Связываем category_id статьи с id категории
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getSelectedCategory()
    {
        return !empty($this->category->id) ? $this->category->id : '';
    }

    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);

        if (!empty($category))
        {
            $this->link('category', $category);
            return true;
        }
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id']);
    }

    public function getSelectedTags()
    {
        return array_column($this->getTags()->select('id')->asArray()->all(), 'id');
    }

    public function saveTags($tags)
    {
        if (is_array($tags))
        {
            ArticleTag::deleteAll(['article_id' => $this->id]);
            foreach ($tags as $tag_id)
            {
                $tag = Tag::findOne($tag_id);
                $this->link('tags', $tag);
            }
        }
    }
}
