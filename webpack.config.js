const path = require('path')

module.exports = {
    entry: {
        study: './src/assets/js/study.js'
    },
    output: {
        filename: 'study.js',
        path: path.resolve(__dirname, 'web/assets/js'),
        libraryTarget: 'var',
        library: 'Study'
    },
    watch: true,
    externals: {
        'vue': 'Vue'
    }
}
