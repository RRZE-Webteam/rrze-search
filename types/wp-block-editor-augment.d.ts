export {};

declare module '@wordpress/block-editor' {
  export type HeadingLevelMixed = 2 | 3 | 4 | 5 | 6 | number[];
  export type HeadingLevel = 2 | 3 | 4 | 5 | 6;

  export interface WPHeadingLevelDropdownProps {
    value: HeadingLevelMixed;
    onChange(level: HeadingLevel): void;
    options?: HeadingLevel[];
  }

  export const HeadingLevelDropdown:
    import('react').ComponentType<WPHeadingLevelDropdownProps>;
}
