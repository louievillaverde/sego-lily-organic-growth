<?php
/**
 * Routine recommendation engine.
 *
 * Default recommendations are generic 2-product pairs. Override per-client
 * by hooking the `lprq_recommendation` filter and returning a custom pair.
 *
 * The shipped defaults match Sego Lily Skincare's product line (Ageless +
 * Renewal tallow butters with sensitivity-aware scent routing). Future
 * clients override with their own product names + shop URLs.
 *
 * @package LPQuizSuite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SLRQ_Recommendations {

	/**
	 * Return a 2-product pair for the given quiz answers.
	 *
	 * @param string $skin_concern  Skin concern answer.
	 * @param string $frustration   Frustration answer.
	 * @return array { primary, secondary, why }
	 */
	public static function pair_for( $skin_concern, $frustration = '' ) {
		$default = self::default_pair( $skin_concern, $frustration );
		return apply_filters( 'lprq_recommendation', $default, $skin_concern, $frustration );
	}

	private static function default_pair( $skin_concern, $frustration ) {
		$is_sensitive  = ( $skin_concern === 'Redness & sensitivity' );
		$is_simplifier = in_array( $frustration, array( 'Too many products', 'Just want something simple' ), true );

		switch ( $skin_concern ) {

			case 'Wrinkles & dark spots':
				return array(
					'primary'   => self::ageless( $is_sensitive ? 'rosewood-lavender' : 'honey-creme' ),
					'secondary' => self::renewal( 'unscented' ),
					'why'       => $is_sensitive
						? 'Wrinkles and dark spots usually show up because your skin&rsquo;s lipid barrier thins with age and sun exposure. Most anti-aging products use harsh actives that flare sensitive skin. Tallow is structurally similar to your skin&rsquo;s own oils, so it won&rsquo;t cause that reaction. Ageless Rosewood Lavender is the gentlest scent in the line. Pair with Renewal Unscented at night when your skin is most vulnerable.'
						: 'Wrinkles and dark spots usually show up because your skin&rsquo;s lipid barrier thins with age and sun exposure. That barrier is what locks in moisture and shields against further damage. Ageless Honey Creme rebuilds it with tallow rich in vitamins A, D, E, and K, the same nutrients your skin naturally produces but slows down on after 35. Use morning and night. Renewal Unscented underneath at night for deep overnight repair.',
				);

			case 'Dryness & tightness':
				return array(
					'primary'   => self::renewal( 'mandarin-orange' ),
					'secondary' => self::ageless( $is_sensitive ? 'rosewood-lavender' : 'honey-creme' ),
					'why'       => 'Tight, dry skin usually means your barrier is compromised. It can&rsquo;t hold the moisture you&rsquo;re putting on it. Most lotions sit on top and evaporate. Tallow absorbs because your skin recognizes the fat structure. Start with Renewal Mandarin Orange in the morning for heavier moisture. Add Ageless Honey Creme at night to reinforce the barrier while you sleep. Most customers report softer skin in 2-3 weeks of consistent use.',
				);

			case 'Redness & sensitivity':
				return array(
					'primary'   => self::renewal( 'unscented' ),
					'secondary' => self::ageless( 'rosewood-lavender' ),
					'why'       => 'Reactive, red skin needs the lowest possible ingredient count. Most products use 15 to 30 ingredients, and your skin reacts to one of them, you just don&rsquo;t know which. Renewal Unscented has five food-grade ingredients. Safe for newborns, post-procedure skin, and rosacea. Pair with Ageless Rosewood Lavender at night for the mildest scented option in the line.',
				);

			case 'Breakouts':
				return array(
					'primary'   => self::renewal( 'unscented' ),
					'secondary' => self::ageless( 'honey-creme' ),
					'why'       => 'Breakouts in adult skin usually mean your barrier is inflamed, not that you&rsquo;re producing too much oil. The wrong ingredients (silicones, harsh sulfates, some actives) make it worse. Tallow is non-comedogenic, it won&rsquo;t clog pores. Renewal Unscented as your daily calms without adding to the problem. Ageless Honey Creme at night helps with tone and texture repair.',
				);

			default:
				return array(
					'primary'   => self::ageless( 'honey-creme' ),
					'secondary' => self::renewal( 'unscented' ),
					'why'       => 'A clean two-product routine that fits most starting points. Ageless Honey Creme in the morning for daily moisture, Renewal Unscented at night for deeper repair.',
				);
		}
	}

	private static function ageless( $scent ) {
		$scents = array(
			'honey-creme'       => 'Honey Creme',
			'rosewood-lavender' => 'Rosewood Lavender',
			'citrus-breeze'     => 'Citrus Breeze',
			'mango'             => 'Mango',
		);
		return array(
			'slug'      => 'ageless-' . $scent,
			'name'      => 'Ageless Tallow Butter',
			'scent'     => $scents[ $scent ] ?? 'Honey Creme',
			'blurb'     => 'Anti-aging. Face, body, hands.',
			'shop_url'  => self::shop_url( 'ageless-tallow-butter' ),
			'image_url' => apply_filters( 'lprq_product_image', '', 'ageless', $scent ),
		);
	}

	private static function renewal( $scent ) {
		$scents = array(
			'mandarin-orange'   => 'Mandarin Orange',
			'cardamom-primrose' => 'Cardamom Primrose',
			'cherry'            => 'Cherry',
			'unscented'         => 'Unscented (Baby and Mom safe)',
		);
		return array(
			'slug'      => 'renewal-' . $scent,
			'name'      => 'Renewal Tallow Butter',
			'scent'     => $scents[ $scent ] ?? 'Unscented',
			'blurb'     => 'Daily moisture. Tone, texture, problem skin.',
			'shop_url'  => self::shop_url( 'renewal-tallow-butter' ),
			'image_url' => apply_filters( 'lprq_product_image', '', 'renewal', $scent ),
		);
	}

	private static function shop_url( $product_slug ) {
		$base = get_option( 'lprq_shop_url', '' );
		if ( ! $base ) {
			$base = home_url( '/shop' );
		}
		return apply_filters( 'lprq_product_url', trailingslashit( $base ) . 'product/' . $product_slug, $product_slug );
	}
}
