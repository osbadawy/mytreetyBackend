<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Address
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $full_name
 * @property int $user_id
 * @property string|null $address
 * @property int|null $country_id
 * @property int $state_id
 * @property int|null $city_id
 * @property float|null $longitude
 * @property float|null $latitude
 * @property string|null $postal_code
 * @property string|null $phone
 * @property int $set_default
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\State|null $state
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereSetDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperAddress {}
}

namespace App\Models{
/**
 * App\Models\AppSettings
 *
 * @property-read \App\Models\Currency|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|AppSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppSettings query()
 * @mixin \Eloquent
 */
	class IdeHelperAppSettings {}
}

namespace App\Models{
/**
 * App\Models\AppTranslation
 *
 * @property int $id
 * @property string|null $lang
 * @property string|null $lang_key
 * @property string|null $lang_value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereLangKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereLangValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAppTranslation {}
}

namespace App\Models{
/**
 * App\Models\Attribute
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeTranslation[] $attribute_translations
 * @property-read int|null $attribute_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeValue[] $attribute_values
 * @property-read int|null $attribute_values_count
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAttribute {}
}

namespace App\Models{
/**
 * App\Models\AttributeCategory
 *
 * @property int $id
 * @property int $category_id
 * @property int $attribute_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAttributeCategory {}
}

namespace App\Models{
/**
 * App\Models\AttributeTranslation
 *
 * @property int $id
 * @property int $attribute_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Attribute|null $attribute
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAttributeTranslation {}
}

namespace App\Models{
/**
 * App\Models\AttributeValue
 *
 * @property int $id
 * @property int $attribute_id
 * @property string $value
 * @property string|null $color_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Attribute|null $attribute
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereColorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereValue($value)
 * @mixin \Eloquent
 */
	class IdeHelperAttributeValue {}
}

namespace App\Models{
/**
 * App\Models\Banner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @mixin \Eloquent
 */
	class IdeHelperBanner {}
}

namespace App\Models{
/**
 * App\Models\Blog
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $banner
 * @property string|null $meta_title
 * @property int|null $meta_img
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BlogCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newQuery()
 * @method static \Illuminate\Database\Query\Builder|Blog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Blog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Blog withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperBlog {}
}

namespace App\Models{
/**
 * App\Models\BlogCategory
 *
 * @property int $id
 * @property string $category_name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Blog[] $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory newQuery()
 * @method static \Illuminate\Database\Query\Builder|BlogCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|BlogCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BlogCategory withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperBlogCategory {}
}

namespace App\Models{
/**
 * App\Models\Brand
 *
 * @property int $id
 * @property string $name
 * @property string|null $logo
 * @property int $top
 * @property string|null $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BrandTranslation[] $brand_translations
 * @property-read int|null $brand_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperBrand {}
}

namespace App\Models{
/**
 * App\Models\BrandTranslation
 *
 * @property int $id
 * @property int $brand_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Brand|null $brand
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrandTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperBrandTranslation {}
}

namespace App\Models{
/**
 * App\Models\BusinessSetting
 *
 * @property int $id
 * @property string $type
 * @property string|null $value
 * @property string|null $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessSetting whereValue($value)
 * @mixin \Eloquent
 */
	class IdeHelperBusinessSetting {}
}

namespace App\Models{
/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int|null $owner_id
 * @property int|null $user_id
 * @property string|null $temp_user_id
 * @property int $address_id
 * @property int|null $product_id
 * @property string|null $variation
 * @property float|null $price
 * @property float|null $tax
 * @property float|null $shipping_cost
 * @property string $shipping_type
 * @property int|null $pickup_point
 * @property float $discount
 * @property string|null $product_referral_code
 * @property string|null $coupon_code
 * @property int $coupon_applied
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address|null $address
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCouponApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePickupPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTempUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereVariation($value)
 * @mixin \Eloquent
 */
	class IdeHelperCart {}
}

namespace App\Models{
/**
 * App\Models\CartProduct
 *
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct query()
 * @mixin \Eloquent
 */
	class IdeHelperCartProduct {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $level
 * @property string $name
 * @property int $order_level
 * @property float $commision_rate
 * @property string|null $banner
 * @property string|null $icon
 * @property int $featured
 * @property int $top
 * @property int $digital
 * @property string|null $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CategoryTranslation[] $category_translations
 * @property-read int|null $category_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $childrenCategories
 * @property-read int|null $children_categories_count
 * @property-read Category|null $parentCategory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sustainability[] $sustainabilities
 * @property-read int|null $sustainabilities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCommisionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOrderLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 * App\Models\CategoryTranslation
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Category|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCategoryTranslation {}
}

namespace App\Models{
/**
 * App\Models\Charity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $operations
 * @property string|null $operations_de
 * @property string $atatchment
 * @property string $country
 * @property string $postal_code
 * @property string $address
 * @property string $city
 * @property string $total_earned
 * @property string $left_earned
 * @property string|null $verification_info
 * @property int|null $verification_status
 * @property string|null $bank_name
 * @property string|null $bank_acc_name
 * @property string|null $bank_acc_no
 * @property string|null $bank_routing_no
 * @property int|null $is_climate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Charity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Charity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Charity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereAtatchment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereBankAccName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereBankAccNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereBankRoutingNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereIsClimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereLeftEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereOperations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereOperationsDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereTotalEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereVerificationInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charity whereVerificationStatus($value)
 * @mixin \Eloquent
 */
	class IdeHelperCharity {}
}

namespace App\Models{
/**
 * App\Models\CharityInvoice
 *
 * @property int $id
 * @property int $charity_id
 * @property string $code
 * @property string $details
 * @property string $file
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereCharityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CharityInvoice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCharityInvoice {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
 * @property float $cost
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CityTranslation[] $city_translations
 * @property-read int|null $city_translations_count
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\State|null $state
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCity {}
}

namespace App\Models{
/**
 * App\Models\CityTranslation
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\City|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCityTranslation {}
}

namespace App\Models{
/**
 * App\Models\Collection
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $sourcing_transportation
 * @property float|null $sourcing_transporationDistance
 * @property float|null $sourcing_exact
 * @property float $manufacturing_energyConsumed
 * @property float $manufacturing_renewableFraction
 * @property float $manufacturing_nonRenewableFraction
 * @property string|null $manufacturing_icons
 * @property float $packaging_mass
 * @property string $packaging_material
 * @property string|null $shipping_transportation
 * @property float|null $shipping_distance
 * @property float|null $shipping_exact
 * @property string|null $use_amount
 * @property float $endoflife_mass
 * @property float $endoflife_recycledAmount
 * @property float $endoflife_thrownAmount
 * @property int $is_green
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereEndoflifeMass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereEndoflifeRecycledAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereEndoflifeThrownAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereIsGreen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereManufacturingEnergyConsumed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereManufacturingIcons($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereManufacturingNonRenewableFraction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereManufacturingRenewableFraction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePackagingMass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePackagingMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereShippingDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereShippingExact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereShippingTransportation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereSourcingExact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereSourcingTransporationDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereSourcingTransportation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUseAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperCollection {}
}

namespace App\Models{
/**
 * App\Models\Color
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Color newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Color newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Color query()
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Color whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperColor {}
}

namespace App\Models{
/**
 * App\Models\CombinedOrder
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $shipping_address
 * @property float $grand_total
 * @property string|null $request
 * @property string|null $receipt
 * @property int|null $type_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CombinedOrder whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperCombinedOrder {}
}

namespace App\Models{
/**
 * App\Models\CommissionHistory
 *
 * @property int $id
 * @property int $order_id
 * @property int $order_detail_id
 * @property int $seller_id
 * @property float $admin_commission
 * @property float $seller_earning
 * @property int|null $payment_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereAdminCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereOrderDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereSellerEarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommissionHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCommissionHistory {}
}

namespace App\Models{
/**
 * App\Models\Conversation
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string|null $title
 * @property int $sender_viewed
 * @property int $receiver_viewed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User|null $receiver
 * @property-read \App\Models\User|null $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereReceiverViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereSenderViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperConversation {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCountry {}
}

namespace App\Models{
/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $code
 * @property string $details
 * @property float $discount
 * @property string $discount_type
 * @property int $start_date
 * @property int $end_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperCoupon {}
}

namespace App\Models{
/**
 * App\Models\CouponUsage
 *
 * @property int $id
 * @property int $user_id
 * @property int $coupon_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponUsage whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperCouponUsage {}
}

namespace App\Models{
/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property float $exchange_rate
 * @property int $status
 * @property string|null $code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCurrency {}
}

namespace App\Models{
/**
 * App\Models\Customer
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperCustomer {}
}

namespace App\Models{
/**
 * App\Models\FaqTranslation
 *
 * @property int $id
 * @property int $faq_id
 * @property string $title
 * @property string $sub_title
 * @property string $lang
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\faq|null $faq
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation whereFaqId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperFaqTranslation {}
}

namespace App\Models{
/**
 * App\Models\Feedback
 *
 * @property int $id
 * @property string $email
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperFeedback {}
}

namespace App\Models{
/**
 * App\Models\FirebaseNotification
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $text
 * @property string $item_type
 * @property int $item_type_id
 * @property int $receiver_id
 * @property int $is_read
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereItemTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirebaseNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperFirebaseNotification {}
}

namespace App\Models{
/**
 * App\Models\FlashDeal
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $start_date
 * @property int|null $end_date
 * @property int $status
 * @property int $featured
 * @property string|null $background_color
 * @property string|null $text_color
 * @property string|null $banner
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlashDealProduct[] $flash_deal_products
 * @property-read int|null $flash_deal_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlashDealTranslation[] $flash_deal_translations
 * @property-read int|null $flash_deal_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereTextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDeal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperFlashDeal {}
}

namespace App\Models{
/**
 * App\Models\FlashDealProduct
 *
 * @property int $id
 * @property int $flash_deal_id
 * @property int $product_id
 * @property float|null $discount
 * @property string|null $discount_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereFlashDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperFlashDealProduct {}
}

namespace App\Models{
/**
 * App\Models\FlashDealTranslation
 *
 * @property int $id
 * @property int $flash_deal_id
 * @property string $title
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\FlashDeal|null $flash_deal
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereFlashDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlashDealTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperFlashDealTranslation {}
}

namespace App\Models{
/**
 * App\Models\GiftCard
 *
 * @property int $id
 * @property string $code
 * @property string $desgin
 * @property float $amount
 * @property string $email
 * @property string $delivary_date
 * @property string $signature
 * @property string $subject
 * @property string $message
 * @property int $is_used
 * @property int $user_id
 * @property int|null $is_paid
 * @property int|null $combined_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereCombinedOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereDelivaryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereDesgin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GiftCard whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperGiftCard {}
}

namespace App\Models{
/**
 * App\Models\GuideTranslation
 *
 * @property int $id
 * @property int $guide_id
 * @property string $title
 * @property string $sub_title
 * @property string $lang
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\guide|null $guide
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation whereGuideId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuideTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperGuideTranslation {}
}

namespace App\Models{
/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $app_lang_code
 * @property int $rtl
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereAppLangCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLanguage {}
}

namespace App\Models{
/**
 * App\Models\ManualPaymentMethod
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $heading
 * @property string|null $description
 * @property string|null $bank_info
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereBankInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManualPaymentMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperManualPaymentMethod {}
}

namespace App\Models{
/**
 * App\Models\Message
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property string|null $message
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Conversation|null $conversation
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperMessage {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $combined_order_id
 * @property int|null $user_id
 * @property int|null $guest_id
 * @property int|null $seller_id
 * @property int|null $assign_delivery_boy
 * @property string|null $shipping_address
 * @property string $shipping_type
 * @property int $pickup_point_id
 * @property string|null $delivery_status
 * @property string|null $payment_type
 * @property int $manual_payment
 * @property string|null $manual_payment_data
 * @property string|null $payment_status
 * @property string|null $payment_details
 * @property float|null $grand_total
 * @property string|null $request
 * @property string|null $receipt
 * @property float $coupon_discount
 * @property string|null $code
 * @property string|null $tracking_code
 * @property int $date
 * @property int $viewed
 * @property int $delivery_viewed
 * @property int $cancel_request
 * @property string|null $cancel_request_at
 * @property int|null $payment_status_viewed
 * @property int $commission_calculated
 * @property string $delivery_history_date
 * @property string|null $tracking_carrier
 * @property string|null $charity
 * @property string|null $climate
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $delivery_boy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderDetail[] $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \App\Models\PickupPoint|null $pickup_point
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProxyPayment[] $proxy_cart_reference_id
 * @property-read int|null $proxy_cart_reference_id_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RefundRequest[] $refund_requests
 * @property-read int|null $refund_requests_count
 * @property-read \App\Models\Shop|null $seller
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAssignDeliveryBoy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCancelRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCancelRequestAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCharity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereClimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCombinedOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCommissionCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryHistoryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGuestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereManualPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereManualPaymentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatusViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePickupPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTrackingCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTrackingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereViewed($value)
 * @mixin \Eloquent
 */
	class IdeHelperOrder {}
}

namespace App\Models{
/**
 * App\Models\OrderDetail
 *
 * @method static where(string $string, $id)
 * @property int $id
 * @property int $order_id
 * @property int|null $seller_id
 * @property int $product_id
 * @property string|null $variation
 * @property float|null $price
 * @property float $tax
 * @property float $shipping_cost
 * @property int|null $quantity
 * @property string $payment_status
 * @property string|null $delivery_status
 * @property string|null $shipping_type
 * @property int|null $pickup_point_id
 * @property string|null $product_referral_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\PickupPoint|null $pickup_point
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\RefundRequest|null $refund_request
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePickupPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereProductReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereVariation($value)
 * @mixin \Eloquent
 */
	class IdeHelperOrderDetail {}
}

namespace App\Models{
/**
 * App\Models\OtpConfiguration
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpConfiguration whereValue($value)
 * @mixin \Eloquent
 */
	class IdeHelperOtpConfiguration {}
}

namespace App\Models{
/**
 * App\Models\Page
 *
 * @property int $id
 * @property string $type
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $content
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $keywords
 * @property string|null $meta_image
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PageTranslation[] $page_translations
 * @property-read int|null $page_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPage {}
}

namespace App\Models{
/**
 * App\Models\PageTranslation
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $content
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Page|null $page
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPageTranslation {}
}

namespace App\Models{
/**
 * App\Models\PasswordReset
 *
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereToken($value)
 * @mixin \Eloquent
 */
	class IdeHelperPasswordReset {}
}

namespace App\Models{
/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $seller_id
 * @property float $amount
 * @property string|null $payment_details
 * @property string|null $payment_method
 * @property string|null $txn_code
 * @property int|null $order_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTxnCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPayment {}
}

namespace App\Models{
/**
 * App\Models\PickupPoint
 *
 * @property int $id
 * @property int $staff_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property int|null $pick_up_status
 * @property int|null $cash_on_pickup_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PickupPointTranslation[] $pickup_point_translations
 * @property-read int|null $pickup_point_translations_count
 * @property-read \App\Models\Staff|null $staff
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereCashOnPickupStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint wherePickUpStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPoint whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPickupPoint {}
}

namespace App\Models{
/**
 * App\Models\PickupPointTranslation
 *
 * @property int $id
 * @property int $pickup_point_id
 * @property string $name
 * @property string $address
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\PickupPoint|null $poickup_point
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation wherePickupPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PickupPointTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPickupPointTranslation {}
}

namespace App\Models{
/**
 * App\Models\Policy
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy query()
 * @mixin \Eloquent
 */
	class IdeHelperPolicy {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $added_by
 * @property int $user_id
 * @property string|null $source
 * @property string|null $external_id
 * @property int|null $category_id
 * @property int|null $brand_id
 * @property string|null $photos
 * @property string|null $thumbnail_img
 * @property string|null $video_provider
 * @property string|null $video_link
 * @property string|null $tags
 * @property string|null $description
 * @property float $unit_price
 * @property float|null $purchase_price
 * @property int $variant_product
 * @property string $attributes
 * @property string|null $choice_options
 * @property string|null $colors
 * @property string|null $variations
 * @property int $todays_deal
 * @property int $published
 * @property int $approved
 * @property string $stock_visibility_state
 * @property int $cash_on_delivery 1 = On, 0 = Off
 * @property int $featured
 * @property int $seller_featured
 * @property int $current_stock
 * @property string|null $unit
 * @property int $min_qty
 * @property int|null $low_stock_quantity
 * @property float|null $discount
 * @property string|null $discount_type
 * @property int|null $discount_start_date
 * @property int|null $discount_end_date
 * @property float|null $starting_bid
 * @property int|null $auction_start_date
 * @property int|null $auction_end_date
 * @property float|null $tax
 * @property string|null $tax_type
 * @property string|null $shipping_type
 * @property string|null $shipping_cost
 * @property int $is_quantity_multiplied 1 = Mutiplied with shipping cost
 * @property int|null $est_shipping_days
 * @property int $num_of_sale
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_img
 * @property string|null $pdf
 * @property string $slug
 * @property int $refundable
 * @property float $earn_point
 * @property float $rating
 * @property string|null $barcode
 * @property int $digital
 * @property int $auction_product
 * @property string|null $file_name
 * @property string|null $file_path
 * @property string|null $external_link
 * @property string|null $external_link_btn
 * @property int $wholesale_product
 * @property string|null $manufactured
 * @property string|null $distributed
 * @property int|null $sustainability_rank
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $collection_id
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Collection|null $collection
 * @property-read \App\Models\FlashDealProduct|null $flash_deal_product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderDetail[] $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductDescription[] $product_descriptions
 * @property-read int|null $product_descriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTranslation[] $product_translations
 * @property-read int|null $product_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductStock[] $stocks
 * @property-read int|null $stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sustainability[] $sustainabilities
 * @property-read int|null $sustainabilities_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wishlist[] $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product physical()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAuctionEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAuctionProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAuctionStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCashOnDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereChoiceOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereColors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDistributed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEarnPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEstShippingDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExternalLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExternalLinkBtn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsQuantityMultiplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLowStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereManufactured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNumOfSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRefundable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellerFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStartingBid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockVisibilityState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSustainabilityRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereThumbnailImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTodaysDeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVariantProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVariations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVideoLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVideoProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWholesaleProduct($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperProduct {}
}

namespace App\Models{
/**
 * App\Models\ProductDescription
 *
 * @property int $id
 * @property string $title
 * @property string $sub_title
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductDescription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperProductDescription {}
}

namespace App\Models{
/**
 * App\Models\ProductRanking
 *
 * @property int $id
 * @property int $product_id
 * @property int $category_id
 * @property float|null $sourcing_score
 * @property float|null $manufacturing_score
 * @property float|null $packaging_score
 * @property float|null $shipping_score
 * @property float|null $use_score
 * @property int|null $end_of_life_score
 * @property int|null $sourcing_level
 * @property int|null $manufacturing_level
 * @property int|null $packaging_level
 * @property int|null $shipping_level
 * @property int|null $use_level
 * @property int|null $end_of_life_level
 * @property int|null $overall_sustainability_ranking
 * @property int $is_calculated
 * @property int|null $icon_calculated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereEndOfLifeLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereEndOfLifeScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereIconCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereIsCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereManufacturingLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereManufacturingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereOverallSustainabilityRanking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking wherePackagingLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking wherePackagingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereShippingLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereShippingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereSourcingLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereSourcingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereUseLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRanking whereUseScore($value)
 * @mixin \Eloquent
 */
	class IdeHelperProductRanking {}
}

namespace App\Models{
/**
 * App\Models\ProductStock
 *
 * @property int $id
 * @property int $product_id
 * @property string $variant
 * @property string|null $sku
 * @property float $price
 * @property int $qty
 * @property int|null $image
 * @property string|null $color
 * @property string|null $value
 * @property string|null $title
 * @property string|null $inventory_item_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereVariant($value)
 * @mixin \Eloquent
 */
	class IdeHelperProductStock {}
}

namespace App\Models{
/**
 * App\Models\ProductTax
 *
 * @property int $id
 * @property int $product_id
 * @property int $tax_id
 * @property float $tax
 * @property string $tax_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTax whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperProductTax {}
}

namespace App\Models{
/**
 * App\Models\ProductTranslation
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $name
 * @property string|null $unit
 * @property string|null $description
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperProductTranslation {}
}

namespace App\Models{
/**
 * App\Models\ProxyPayment
 *
 * @property int $id
 * @property string $payment_type
 * @property string $reference_id
 * @property int|null $order_id
 * @property int|null $package_id
 * @property int $user_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyPayment whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperProxyPayment {}
}

namespace App\Models{
/**
 * App\Models\RefundRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property int $order_detail_id
 * @property int $seller_id
 * @property int $seller_approval
 * @property int $admin_approval
 * @property float $refund_amount
 * @property string|null $reason
 * @property string|null $method
 * @property string|null $details
 * @property int|null $qty
 * @property int $admin_seen
 * @property int $refund_status
 * @property string|null $reject_reason
 * @property string|null $attachment
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\OrderDetail|null $orderDetail
 * @property-read \App\Models\User|null $seller
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereAdminApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereAdminSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereOrderDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereRefundStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereRejectReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereSellerApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundRequest whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperRefundRequest {}
}

namespace App\Models{
/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $user_id
 * @property int $rating
 * @property string $comment
 * @property int $status
 * @property int $viewed
 * @property string|null $email
 * @property string|null $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereViewed($value)
 * @mixin \Eloquent
 */
	class IdeHelperReview {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $permissions
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RoleTranslation[] $role_translations
 * @property-read int|null $role_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRole {}
}

namespace App\Models{
/**
 * App\Models\RoleTranslation
 *
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Role|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperRoleTranslation {}
}

namespace App\Models{
/**
 * App\Models\Search
 *
 * @property int $id
 * @property string $query
 * @property int $count
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Search newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search query()
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSearch {}
}

namespace App\Models{
/**
 * App\Models\Seller
 *
 * @property int $id
 * @property int $user_id
 * @property float $rating
 * @property int $num_of_reviews
 * @property int $num_of_sale
 * @property int|null $seller_package_id
 * @property string|null $invalid_at
 * @property int $verification_status
 * @property string|null $verification_info
 * @property int $cash_on_delivery_status
 * @property float $admin_to_pay
 * @property string|null $bank_name
 * @property string|null $bank_acc_name
 * @property string|null $bank_acc_no
 * @property int|null $bank_routing_no
 * @property int $bank_payment_status
 * @property string|null $bank_iban
 * @property string|null $paypal_account
 * @property string|null $shopify_apikey
 * @property string|null $shopify_password
 * @property string|null $shopify_url
 * @property string|null $shopify_ver
 * @property string|null $shopify_accessToken
 * @property string|null $xml_file
 * @property string|null $description
 * @property string|null $banner
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereAdminToPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankAccName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankAccNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankPaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBankRoutingNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereCashOnDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereInvalidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereNumOfReviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereNumOfSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller wherePaypalAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereSellerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereShopifyAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereShopifyApikey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereShopifyPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereShopifyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereShopifyVer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereVerificationInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereVerificationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereXmlFile($value)
 * @mixin \Eloquent
 */
	class IdeHelperSeller {}
}

namespace App\Models{
/**
 * App\Models\SellerPackage
 *
 * @property int $id
 * @property string|null $name
 * @property float $amount
 * @property int $product_upload_limit
 * @property string|null $logo
 * @property int $duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SellerPackageTranslation[] $seller_package_translations
 * @property-read int|null $seller_package_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereProductUploadLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSellerPackage {}
}

namespace App\Models{
/**
 * App\Models\SellerPackagePayment
 *
 * @property int $id
 * @property int $user_id
 * @property int $seller_package_id
 * @property string|null $payment_method
 * @property string|null $payment_details
 * @property int $approval
 * @property int $offline_payment 1=offline payment
 * 2=online paymnet
 * @property string|null $reciept
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\SellerPackage|null $seller_package
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereOfflinePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereReciept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereSellerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackagePayment whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperSellerPackagePayment {}
}

namespace App\Models{
/**
 * App\Models\SellerPackageTranslation
 *
 * @property int $id
 * @property int $seller_package_id
 * @property string $name
 * @property string $lang
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\SellerPackage|null $seller_package
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereSellerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerPackageTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSellerPackageTranslation {}
}

namespace App\Models{
/**
 * App\Models\SellerWithdrawRequest
 *
 * @property int $id
 * @property int|null $user_id
 * @property float|null $amount
 * @property string|null $message
 * @property int|null $status
 * @property int|null $viewed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Seller|null $seller
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerWithdrawRequest whereViewed($value)
 * @mixin \Eloquent
 */
	class IdeHelperSellerWithdrawRequest {}
}

namespace App\Models{
/**
 * App\Models\Shop
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string|null $logo
 * @property string|null $sliders
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $facebook
 * @property string|null $google
 * @property string|null $twitter
 * @property string|null $youtube
 * @property string|null $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $pick_up_point_id
 * @property float $shipping_cost
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $description
 * @property string|null $attachment
 * @property float|null $delivery_pickup_latitude
 * @property float|null $delivery_pickup_longitude
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDeliveryPickupLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDeliveryPickupLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereGoogle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePickUpPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSliders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shop whereYoutube($value)
 * @mixin \Eloquent
 */
	class IdeHelperShop {}
}

namespace App\Models{
/**
 * App\Models\Slider
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider query()
 * @mixin \Eloquent
 */
	class IdeHelperSlider {}
}

namespace App\Models{
/**
 * App\Models\SmsTemplate
 *
 * @property int $id
 * @property string $identifier
 * @property string $sms_body
 * @property string|null $template_id
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereSmsBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSmsTemplate {}
}

namespace App\Models{
/**
 * App\Models\Staff
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\PickupPoint|null $pick_up_point
 * @property-read \App\Models\Role|null $role
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Staff query()
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Staff whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperStaff {}
}

namespace App\Models{
/**
 * App\Models\State
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperState {}
}

namespace App\Models{
/**
 * App\Models\SubCategory
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SubSubCategory[] $subSubCategories
 * @property-read int|null $sub_sub_categories_count
 */
	class IdeHelperSubCategory {}
}

namespace App\Models{
/**
 * App\Models\SubSubCategory
 *
 * @property int $id
 * @property int $sub_category_id
 * @property string $name
 * @property string $brands
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereBrands($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubSubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\SubCategory|null $subCategory
 */
	class IdeHelperSubSubCategory {}
}

namespace App\Models{
/**
 * App\Models\Subscriber
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSubscriber {}
}

namespace App\Models{
/**
 * App\Models\Sustainability
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property int $group_id
 * @property string|null $slug
 * @property int|null $weight
 * @property float|null $price
 * @property float|null $emisson_reduction
 * @property string|null $ui_sepertion
 * @property string|null $required_documents
 * @property int|null $sourcing
 * @property int|null $manufacturing
 * @property int|null $packaging
 * @property int|null $shipping
 * @property int|null $use
 * @property int|null $end_of_life
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SustainabilityTranslation[] $sustainability_translations
 * @property-read int|null $sustainability_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereEmissonReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereEndOfLife($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereManufacturing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability wherePackaging($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereRequiredDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereSourcing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereUiSepertion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sustainability whereWeight($value)
 * @mixin \Eloquent
 */
	class IdeHelperSustainability {}
}

namespace App\Models{
/**
 * App\Models\SustainabilityRequest
 *
 * @property int $id
 * @property int $user_id
 * @property int $sustainability_id
 * @property int|null $product_id
 * @property string|null $files
 * @property int|null $combined_order_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sustainability|null $sustainability
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereCombinedOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereSustainabilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityRequest whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperSustainabilityRequest {}
}

namespace App\Models{
/**
 * App\Models\SustainabilityTranslation
 *
 * @property int $id
 * @property int $sustainability_id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property string $lang
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sustainability|null $sustainability
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereSustainabilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SustainabilityTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSustainabilityTranslation {}
}

namespace App\Models{
/**
 * App\Models\Tax
 *
 * @property int $id
 * @property string $name
 * @property int $tax_status 0 = Inactive, 1 = Active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTax[] $product_taxes
 * @property-read int|null $product_taxes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereTaxStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTax {}
}

namespace App\Models{
/**
 * App\Models\Ticket
 *
 * @property int $id
 * @property int $code
 * @property int $user_id
 * @property string $subject
 * @property string|null $details
 * @property string|null $files
 * @property string $status
 * @property int $viewed
 * @property int $client_viewed
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TicketReply[] $ticketreplies
 * @property-read int|null $ticketreplies_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereClientViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereViewed($value)
 * @mixin \Eloquent
 */
	class IdeHelperTicket {}
}

namespace App\Models{
/**
 * App\Models\TicketReply
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $reply
 * @property string|null $files
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Ticket|null $ticket
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperTicketReply {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $gateway
 * @property string|null $payment_type
 * @property string|null $additional_content
 * @property string|null $mpesa_request
 * @property string|null $mpesa_receipt
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMpesaReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMpesaRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperTransaction {}
}

namespace App\Models{
/**
 * App\Models\Translation
 *
 * @property int $id
 * @property string|null $lang
 * @property string|null $lang_key
 * @property string|null $lang_value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLangKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLangValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTranslation {}
}

namespace App\Models{
/**
 * App\Models\Upload
 *
 * @property int $id
 * @property string|null $file_original_name
 * @property string|null $file_name
 * @property int|null $user_id
 * @property int|null $file_size
 * @property string|null $extension
 * @property string|null $type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newQuery()
 * @method static \Illuminate\Database\Query\Builder|Upload onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload query()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFileOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Upload withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Upload withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperUpload {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $referred_by
 * @property string|null $provider_id
 * @property string $user_type
 * @property string $name
 * @property string|null $email
 * @property string|null $email_verified_at
 * @property string|null $verification_code
 * @property string|null $new_email_verificiation_code
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $device_token
 * @property string|null $avatar
 * @property string|null $avatar_original
 * @property string|null $address
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $phone
 * @property float $balance
 * @property int $banned
 * @property string|null $referral_code
 * @property int|null $customer_package_id
 * @property int|null $remaining_uploads
 * @property string|null $url
 * @property int $walkthrough
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $displayname
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read \App\Models\Charity|null $charity
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\Seller|null $seller
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SellerPackagePayment[] $seller_package_payments
 * @property-read int|null $seller_package_payments_count
 * @property-read \App\Models\Shop|null $shop
 * @property-read \App\Models\Staff|null $staff
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallet[] $wallets
 * @property-read int|null $wallets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wishlist[] $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCustomerPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDisplayname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNewEmailVerificiationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReferredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRemainingUploads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalkthrough($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\Wallet
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property string|null $payment_method
 * @property string|null $payment_details
 * @property int $approval
 * @property int $offline_payment
 * @property string|null $reciept
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereOfflinePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereReciept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperWallet {}
}

namespace App\Models{
/**
 * App\Models\Wishlist
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperWishlist {}
}

namespace App\Models{
/**
 * App\Models\faq
 *
 * @property int $id
 * @property string $title
 * @property string $sub_title
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FaqTranslation[] $faq_translations
 * @property-read int|null $faq_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperfaq {}
}

namespace App\Models{
/**
 * App\Models\guide
 *
 * @property int $id
 * @property string $title
 * @property string $sub_title
 * @property string $attachment
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GuideTranslation[] $guide_translations
 * @property-read int|null $guide_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|guide newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|guide newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|guide query()
 * @method static \Illuminate\Database\Eloquent\Builder|guide whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|guide whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|guide whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|guide whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|guide whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|guide whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|guide whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperguide {}
}

