import {
  TextControl,
  PanelBody,
  SelectControl
} from "@wordpress/components";
import {
  InspectorControls,
  HeadingLevelDropdown,
  BlockControls,
  RichText
} from "@wordpress/block-editor"
import {
  useBlockProps,
} from "@wordpress/block-editor";
import {__} from "@wordpress/i18n";

interface EditProps {
  attributes: {
    width: "content-size" | "full-grid";
    headingLevel: 2 | 3 | 4 | 5 | 6;
    heading: string;
  },
  setAttributes: (attributes: Partial<EditProps["attributes"]>) => void;
  blockProps: string[];
}

export default function Edit({attributes, setAttributes}: EditProps) {
  const {width, heading, headingLevel} = attributes;

  const blockProps = useBlockProps({
    className: `fau-global-search-wrapper fau-global-search-wrapper--${width}`,
  });

  const HEADING_LEVELS = [2, 3, 4, 5, 6];

  const onChangeWidth = (newWidth: "content-size" | "full-grid") => {
    setAttributes({width: newWidth});
  };

  const onChangeHeadingLevel = (newHeadingLevel: 2 | 3 | 4 | 5 | 6) => {
    setAttributes({headingLevel: newHeadingLevel});
  };

  const onChangeHeading = (newHeading: string) => {
    setAttributes({heading: newHeading});
  };

  return (
    <>
      {heading.length > 0 && (
          <BlockControls group="block">
              <HeadingLevelDropdown
                  options={[2, 3, 4, 5, 6]}
                  value={headingLevel}
                  onChange={onChangeHeadingLevel}
              ></HeadingLevelDropdown>
          </BlockControls>
      )}
      <InspectorControls>
        <PanelBody title={__('Content Settings', 'fau-elemental')}>
          <TextControl
            label={__('Heading', 'fau-elemental')}
            value={heading}
            onChange={onChangeHeading}
            help={__(
              'Optional heading text to display above the search form',
              'fau-elemental'
            )}
          />
        </PanelBody>
        {heading.length > 0 && (
        <PanelBody title={__('Layout Settings', 'fau-elemental')}>
          <SelectControl
            label={__('Width', 'fau-elemental')}
            value={width}
            options={[
              {
                label: __(
                  'Content Size (with search scope & advanced features)',
                  'fau-elemental'
                ),
                value: 'content-size',
              },
              {
                label: __(
                  'Full Grid (simple search only)',
                  'fau-elemental'
                ),
                value: 'full-grid',
              },
            ]}
            onChange={onChangeWidth}
            help={__(
              'Content Size: for editorial content, includes search scope selection and advanced features. Full Grid: for wide teaser components, simple search only.',
              'fau-elemental'
            )}
          />
        </PanelBody>
      )}
      </InspectorControls>
      <div className="fau-global-search__outer-wrapper">
        <div {...blockProps}>
          {width === 'full-grid' && heading && (
            <RichText placeholder={__("Heading", "rrze-search")} tagName={`h${headingLevel}`} allowedFormats={[]} className="fau-global-search__heading fau-global-search__heading--full-grid" value={heading} onChange={onChangeHeading} />
          )}
          {width !== 'full-grid' && heading && (
            <RichText placeholder={__("Heading", "rrze-search")} tagName={`h${headingLevel}`} allowedFormats={[]} className="fau-global-search__heading" value={heading} onChange={onChangeHeading} />
          )}
          <form className="fau-global-search fau-global-search__form">
            <div
              className={`fau-global-search__input-wrapper${
                width === 'full-grid'
                  ? ' fau-global-search__input-wrapper--full-grid'
                  : ''
              }`}
            >
              <input
                type="search"
                className="fau-global-search__input"
                placeholder={__('Search…', 'fau-elemental')}
                autoComplete="off"
                disabled
              />
              <button
                type="submit"
                className="fau-global-search__button"
                disabled
              >
								<span className="fau-global-search__button-text">
									{__('Search', 'fau-elemental')}
								</span>
                <span
                  className="fau-global-search__button-icon"
                  aria-hidden="true"
                ></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </>
  );
}
