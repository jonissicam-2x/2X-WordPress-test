const path = require('path');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerWebpackPlugin = require('css-minimizer-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const packageJson = require('./package.json');

module.exports = (env, argv) => { // <-- Change to a function
  // Set the mode to 'development' for a non-minified output with source maps
  const isProduction = argv.mode === 'production';
  return {
        mode: argv.mode, // <-- Use the mode from the command line

        // Define your multiple entry points
        entry: {
            main_js: './src/js/app.js',
            main_css: './src/css/app.scss',
            team_css: './src/css/team.scss',
            fonts: './src/css/fonts.css',
            gutenberg_css: './src/css/gutenberg.scss'
        },

        // Configure where Webpack outputs the bundled files
        output: {
          filename: 'dist/js/[name].min.[contenthash].js',
          // The output directory
          path: path.resolve(__dirname),
          publicPath: '',
        },

        // Define rules for how different file types are handled
        module: {
            rules: [
                {
                    test: /\.(png|jpe?g|gif|svg)$/i,
                    // Set the type to 'asset'
                    type: 'asset',
                    parser: {
                      // Disable the automatic creation of a separate file
                      dataUrlCondition: {
                        maxSize: 0, // Set this to 0 to always output a separate file
                      },
                    },
                    generator: {
                        // This is the key part that prevents the path from being rewritten
                        // by keeping the original relative path
                        filename: '[path][name][ext]',
                        publicPath: '../../',
                    },
                },
                {
                    // Capture all common font extensions
                    test: /\.(woff|woff2|eot|ttf|otf)$/i,
                    type: 'asset/resource',
                    generator: {
                        filename: '[path][name][ext]',
                        publicPath: '../../',
                    }
                },
                // Rule for JavaScript files (transpiles with Babel)
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env'],
                        },
                    },
                },
                // NEW RULE: Rule for Sass/SCSS files
                {
                    test: /\.s[ac]ss$/i,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        {
                            loader: 'sass-loader',
                            // The key change: add sassOptions here
                            options: {
                                // Prefer `dart-sass`, even if `sass-embedded` is available
                                implementation: require("sass"),
                            }
                        },
                    ],
                },
                // Rule for CSS files
                {
                    test: /\.css$/,
                    use: [
                        // Use MiniCssExtractPlugin's loader instead of style-loader
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                    ],
                },
            ],
        },

        // Define plugins
        plugins: [
            new MiniCssExtractPlugin({
              filename: 'dist/css/[name].min.[contenthash].css' // Specify the output filename
            }),
            new WebpackManifestPlugin({
                fileName: 'manifest.json',
                // Use the generate option to add custom content
                generate: (seed, files, entrypoints) => {
                    const manifestFiles = files.reduce((manifest, file) => {
                        manifest[file.name] = file.path;
                        return manifest;
                    }, seed);

                    // Add custom properties to the manifest object
                    const manifest = {
                        name: packageJson.name,
                        version: packageJson.version,
                        description: packageJson.description,
                        isProduction: isProduction,
                        buildTimestamp: new Date().toISOString(),
                        assets: manifestFiles,
                    };
                    
                    return manifest;
                },
            }),
            new CleanWebpackPlugin({
                cleanOnceBeforeBuildPatterns: [
                    'dist/*'
                ]
            }),
            // Add the new plugin for production builds
            isProduction && new CssMinimizerWebpackPlugin(),
        ].filter(Boolean),
        // The key change: add the stats property
        stats: {
            errorDetails: true,
        },    
        // Add devtool for source maps in development
        devtool: isProduction ? false : 'source-map',
    };
};