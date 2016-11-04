# NARA Renewables 2.0 Theme

NARA's 2.0 site

## Working with the NARA theme

Requirements:

* Install Grunt - `npm install -g grunt-cli`.
* Install NPM dependencies - `npm install`.

On a successful run of `grunt` or `grunt default`:

* All CSS files in `src/css` will be linted and concatenated into `style.css`.
* All Spine JS files in `src/js` will be linted and concatenated into `js/spine.min.js`.

## Bumping version numbers

When a new version of the theme is ready:

1. Check out the `master` branch and make sure you have pulled all changes.
2. Check out a new branch for the version bump `version/x.x.x`
3. Bump the version number in these places:
    * `src/css/00-banner.css`
    * `functions.php`
    * `package.json`
4. Run `grunt` so that the version number in banner is applied to the stylesheet.
5. Commit these changes to the `version/x.x.x` branch.
6. Push changes to GitHub and open a pull request.
7. Once all Travis checks have passed, merge the pull request.
8. Tag the new version in GitHub for deployment.
