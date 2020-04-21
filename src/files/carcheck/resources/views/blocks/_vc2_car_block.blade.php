<?php
// Create id attribute allowing for custom "anchor" value.
		$id = 'car-' . $block['id'];
		if( !empty($block['anchor']) ) {
		    $id = $block['anchor'];
		}

		// Create class attribute allowing for custom "className" and "align" values.
		$className = 'vc-car';
		if( !empty($block['className']) ) {
		    $className .= ' ' . $block['className'];
		}
		if( !empty($block['align']) ) {
		    $className .= ' align' . $block['align'];
		}
		if( $is_preview ) {
		    $className .= ' is-admin';
		}	
		$terms = get_terms( 
			array(
				'taxonomy' => 'carorder-cat',
				'orderby' => 'post-date',
				'order' => 'DESC',
				'hide_empty' => true,
			) 
		);
		?>
		<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" >
			<div class="container">
				<div class="select-box">
					<div class="gr-option">
						<div class="gr-radio">
							<div class="row">
								@php
									$i = 0;
								@endphp
								@foreach ($terms as $term)
								@php
									$check = ($i == 0) ? 'checked' :'';
								@endphp
									<div class="col-md-6">
										<div class="content-gr">
											<label>{{ $term->name }}</label>
											<input type="radio" class="form-control" name="cartrip" value="{{ $term->term_id }}" {{ $check }}>
											<span class="bg"></span>
										</div>
									</div>
									@php
										$i++;
									@endphp
								@endforeach
							</div>
						</div>
						<div class="row select_type">
							<div class="col-md-6">
								@php
									$query_type = new \WP_Query( array(
										'post_type'           => 'car-order',
										'orderby'             => 'post-date',
										'order'               => 'DESC',
										'ignore_sticky_posts' => true,
										'posts_per_page'      => -1,
										'paged'               => false,
										'tax_query'           => array(
											array(
												'taxonomy'  => 'carorder-cat',
												'field'     => 'term_id',
												'terms'     => $terms[0]->term_id,
												'operator'  => 'IN',
											)
										),
										
									) );
									
									$get_meta = get_field( 'time_slots', $query_type->posts[0]->ID );	
																
								@endphp	
								<select name="car-type" id="car_type">
									
										@while ($query_type->have_posts())                 
											{!! $query_type->the_post() !!}
											<option value="{{ get_the_ID() }}">{{ get_the_title() }}</option>
											@endwhile						
								</select>
							</div>
							<div class="col-md-6">
								<select name="time-slot" id="time_slot">
									@php
											$i =0;
										@endphp
										@foreach ($get_meta as $item) 
											<option value="{{$i}}">{{ $item['time_slot'] }}</option>
											@php
												$i++;
											@endphp
										@endforeach
								</select>
							</div>
						</div>
						<div class="col-12">
							<button id="btn-carorder" class="btn-car">Xem giá</button>
						</div>
					</div>
					<div class="row">	
						<div class="col-12">
							<table class="table-price">
								<thead>
									<tr>
										<th>Vị trí địa lý</th>
										<th>Giá thông thường</th>
										<th>Giá khuyến mãi</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($get_meta[0]['price_trip'] as $item)
										<tr>
											<td>{{ $item['location'] }}</td>
											<td>{{ $item['default_price'] }}</td>
											<td>{{ $item['sale_price'] }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</div>
		</div>
<?php     