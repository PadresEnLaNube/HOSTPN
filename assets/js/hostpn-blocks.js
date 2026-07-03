/**
 * HOSTPN Gutenberg Blocks
 *
 * @package    hostpn
 * @subpackage hostpn/assets/js
 */

(function() {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor || wp.editor;
  const { PanelBody, ToggleControl, RangeControl, Button, Placeholder, Disabled } = wp.components;
  const { __ } = wp.i18n;
  const { createElement: el, Fragment } = wp.element;
  const ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender;

  registerBlockType('hostpn/carousel', {
    title: __('HOSTPN Carousel', 'hostpn'),
    description: __('Display accommodation gallery as a carousel', 'hostpn'),
    icon: 'images-alt2',
    category: 'media',
    apiVersion: 3,
    attributes: {
      images: {
        type: 'array',
        default: [],
      },
      postId: {
        type: 'number',
        default: 0,
      },
      showNav: {
        type: 'boolean',
        default: true,
      },
      showDots: {
        type: 'boolean',
        default: true,
      },
      showCounter: {
        type: 'boolean',
        default: false,
      },
      showAutoplay: {
        type: 'boolean',
        default: false,
      },
      autoplay: {
        type: 'boolean',
        default: false,
      },
      autoplaySpeed: {
        type: 'number',
        default: 5000,
      },
      speed: {
        type: 'number',
        default: 500,
      },
      loop: {
        type: 'boolean',
        default: true,
      },
      itemsDesktop: {
        type: 'number',
        default: 1,
      },
      itemsMobile: {
        type: 'number',
        default: 1,
      },
    },

    edit: function(props) {
      const { attributes, setAttributes } = props;
      const {
        images,
        postId,
        showNav,
        showDots,
        showCounter,
        showAutoplay,
        autoplay,
        autoplaySpeed,
        speed,
        loop,
        itemsDesktop,
        itemsMobile,
      } = attributes;

      const onSelectImages = function(media) {
        setAttributes({
          images: media.map(function(img) {
            return {
              id: img.id,
              url: img.url,
              alt: img.alt || ''
            };
          })
        });
      };

      const removeImage = function(index) {
        const newImages = images.filter(function(img, i) {
          return i !== index;
        });
        setAttributes({ images: newImages });
      };

      return el(Fragment, {},
        el(InspectorControls, {},
          el(PanelBody, {
            title: __('Images', 'hostpn'),
            initialOpen: true
          },
            el(MediaUploadCheck, {},
              el(MediaUpload, {
                onSelect: onSelectImages,
                allowedTypes: ['image'],
                multiple: true,
                gallery: true,
                value: images.map(function(img) { return img.id; }),
                render: function(obj) {
                  return el(Button, {
                    onClick: obj.open,
                    variant: 'primary',
                    style: { marginBottom: '10px' }
                  }, images.length === 0 ? __('Select Images', 'hostpn') : __('Edit Images', 'hostpn'));
                }
              })
            ),
            images.length > 0 && el('div', { style: { marginTop: '10px' } },
              el('p', { style: { fontSize: '12px', color: '#666' } },
                images.length + ' ' + (images.length === 1 ? __('image selected', 'hostpn') : __('images selected', 'hostpn'))
              ),
              el('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '5px', marginTop: '10px' } },
                images.map(function(image, index) {
                  return el('div', {
                    key: image.id,
                    style: { position: 'relative' }
                  },
                    el('img', {
                      src: image.url,
                      alt: image.alt,
                      style: { width: '100%', height: '60px', objectFit: 'cover', borderRadius: '4px' }
                    }),
                    el(Button, {
                      onClick: function() { removeImage(index); },
                      isDestructive: true,
                      isSmall: true,
                      style: { position: 'absolute', top: '2px', right: '2px', minWidth: '20px', height: '20px', padding: '0' }
                    }, '×')
                  );
                })
              )
            )
          ),
          el(PanelBody, {
            title: __('Display Settings', 'hostpn'),
            initialOpen: false
          },
            el(ToggleControl, {
              label: __('Show Navigation Arrows', 'hostpn'),
              checked: showNav,
              onChange: function(value) { setAttributes({ showNav: value }); },
              help: __('Show previous/next navigation arrows', 'hostpn')
            }),
            el(ToggleControl, {
              label: __('Show Dots', 'hostpn'),
              checked: showDots,
              onChange: function(value) { setAttributes({ showDots: value }); },
              help: __('Show dot indicators below carousel', 'hostpn')
            }),
            el(ToggleControl, {
              label: __('Show Counter', 'hostpn'),
              checked: showCounter,
              onChange: function(value) { setAttributes({ showCounter: value }); },
              help: __('Show slide counter (1/5, 2/5, etc.)', 'hostpn')
            }),
            el(ToggleControl, {
              label: __('Show Autoplay Button', 'hostpn'),
              checked: showAutoplay,
              onChange: function(value) { setAttributes({ showAutoplay: value }); },
              help: __('Show play/pause button for autoplay control', 'hostpn')
            })
          ),
          el(PanelBody, {
            title: __('Autoplay Settings', 'hostpn'),
            initialOpen: false
          },
            el(ToggleControl, {
              label: __('Enable Autoplay', 'hostpn'),
              checked: autoplay,
              onChange: function(value) { setAttributes({ autoplay: value }); },
              help: __('Automatically advance slides', 'hostpn')
            }),
            el(RangeControl, {
              label: __('Autoplay Speed (ms)', 'hostpn'),
              value: autoplaySpeed,
              onChange: function(value) { setAttributes({ autoplaySpeed: value }); },
              min: 1000,
              max: 10000,
              step: 500,
              help: __('Time each slide is displayed (in milliseconds)', 'hostpn')
            })
          ),
          el(PanelBody, {
            title: __('Animation Settings', 'hostpn'),
            initialOpen: false
          },
            el(RangeControl, {
              label: __('Transition Speed (ms)', 'hostpn'),
              value: speed,
              onChange: function(value) { setAttributes({ speed: value }); },
              min: 100,
              max: 2000,
              step: 100,
              help: __('Speed of slide transition animation (in milliseconds)', 'hostpn')
            }),
            el(ToggleControl, {
              label: __('Loop', 'hostpn'),
              checked: loop,
              onChange: function(value) { setAttributes({ loop: value }); },
              help: __('Return to first slide after last slide', 'hostpn')
            })
          ),
          el(PanelBody, {
            title: __('Responsive Settings', 'hostpn'),
            initialOpen: false
          },
            el(RangeControl, {
              label: __('Items per slide (Desktop)', 'hostpn'),
              value: itemsDesktop,
              onChange: function(value) { setAttributes({ itemsDesktop: value }); },
              min: 1,
              max: 4,
              help: __('Number of items to show per slide on desktop', 'hostpn')
            }),
            el(RangeControl, {
              label: __('Items per slide (Mobile)', 'hostpn'),
              value: itemsMobile,
              onChange: function(value) { setAttributes({ itemsMobile: value }); },
              min: 1,
              max: 2,
              help: __('Number of items to show per slide on mobile', 'hostpn')
            })
          )
        ),
        images.length === 0 ? el(Placeholder, {
          icon: 'images-alt2',
          label: __('HOSTPN Carousel', 'hostpn'),
          instructions: __('Select images from the sidebar to create a carousel', 'hostpn')
        },
          el(MediaUploadCheck, {},
            el(MediaUpload, {
              onSelect: onSelectImages,
              allowedTypes: ['image'],
              multiple: true,
              gallery: true,
              value: [],
              render: function(obj) {
                return el(Button, {
                  onClick: obj.open,
                  variant: 'primary'
                }, __('Select Images', 'hostpn'));
              }
            })
          )
        ) : el(Disabled, {},
          el('div', { className: 'hostpn-carousel-block-preview', style: { border: '1px solid #ddd', padding: '20px', borderRadius: '4px', background: '#fff' } },
            el('p', { style: { marginBottom: '10px', fontWeight: 'bold' } }, __('Carousel Preview', 'hostpn')),
            el('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(150px, 1fr))', gap: '10px' } },
              images.map(function(image) {
                return el('img', {
                  key: image.id,
                  src: image.url,
                  alt: image.alt,
                  style: { width: '100%', height: '150px', objectFit: 'cover', borderRadius: '4px' }
                });
              })
            ),
            el('p', { style: { marginTop: '10px', fontSize: '12px', color: '#666' } },
              __('The actual carousel will be displayed on the front-end with all your settings applied.', 'hostpn')
            )
          )
        )
      );
    },

    save: function() {
      // Server-side rendering, so return null
      return null;
    },
  });
})();
