"use strict";

/**
 *
 * @param {Array.<Mutation>} mutations
 * @param {Mutation|null} currentMutation
 * @constructor
 */
function MutationsManager(mutations, currentMutation) {
    var self = this;

    this.getMutations = function () {
        return mutations.slice(0);
    };

    /**
     * @param {Array.<Mutator>} mutators
     * @returns {Mutation}
     */
    this.mutate = function (mutators) {
        var mutation = new Mutation(mutators, currentMutation, Date.now());

        if (currentMutation === null) {
            return currentMutation = self._mutateOrigin(mutation);
        } else {
            return currentMutation = self._mutateCurrentMutation(mutation);
        }
    };

    /**
     * @param {Mutation} mutation
     * @returns {Mutation}
     * @private
     */
    this._mutateOrigin = function (mutation) {
        var existingMutation = self._findMutationWithSameMutators(mutation, mutations);

        if (existingMutation === null) {
            mutations.push(mutation);
            return mutation;
        }

        return existingMutation;
    };

    /**
     * @param {Mutation} mutation
     * @returns {Mutation}
     * @private
     */
    this._mutateCurrentMutation = function (mutation) {
        var existingMutation = self._findMutationWithSameMutators(mutation, currentMutation.getChildren());

        if (existingMutation === null) {
            currentMutation.addChild(mutation);
            return mutation;
        }

        return existingMutation;
    };

    /**
     * @param {Mutation} mutation
     * @param {Array.<Mutation>} mutations
     * @returns {Mutation}
     * @private
     */
    this._findMutationWithSameMutators = function (mutation, mutations) {
        for (var i = 0; i < mutations.length; i++) {
            var m = mutations[i];
            if (m.hasEqualMutators(mutation)) return m;
        }

        return null;
    };

    /**
     * @returns {Mutation}
     */
    this.getCurrentMutation = function () {
        return currentMutation;
    };

    /**
     * @param {Mutation} mutation
     */
    this.setCurrentMutation = function (mutation) {  //TODO: CHECK IF MUTATION EXISTS IN all mutations and their children.
        currentMutation = mutation;
    };
}



/**
 * @param {Array.<Mutator>} mutators
 * @param {Mutation} parent
 * @param {number} timestamp
 * @constructor
 */
function Mutation(mutators, parent, timestamp) {
    /** @type {Array.<Mutation>} */
    var children = [];

    /** @type {Array.<String>} */
    var tags = [];

    /**
     * @returns {Array.<String>}
     */
    this.getTags = function () {
        return tags.slice(0);
    };

    /**
     * @param {Array.<String>} newTags
     * @protected
     */
    this._setTags = function (newTags) {
        tags = newTags.slice(0);
    };

    /**
     * @param {String} tag
     */
    this.addTag = function (tag) {
        if (tag === '') return;
        for (var i = 0; i < tags.length; i++) {
            if (tags[i] === tag) return;
        }
        tags.push(tag);
    };

    /**
     * @param {String} tag
     */
    this.removeTag = function (tag) {
        var index = tags.indexOf(tag);
        if (index > -1) tags.splice(index, 1);
    };

    /**
     * @returns {number}
     */
    this.getTimestamp = function () {
        return timestamp;
    };

    /**
     * @returns {Array.<Mutator>}
     */
    this.getMutators = function () {
        return mutators.slice(0);
    };

    /**
     * @returns {Mutation}
     */
    this.getParent = function () {
        return parent;
    };

    /**
     * @returns {Array.<Mutation>}
     */
    this.getChildren = function () {
        return children.slice(0);
    };

    /**
     * @param {Mutation} mutation
     */
    this.addChild = function (mutation) {
        children.push(mutation);
    };

    /**
     * @param {Array.<Mutation>} mutations
     * @protected
     */
    this._setChildren = function (mutations) {
        children = mutations.slice(0);
    };

    /**
     * @param {Mutation} mutation
     */
    this.hasEqualMutators = function (mutation) {
        var otherMutators = mutation.getMutators();
        if (mutators.length !== otherMutators.length) return false;

        for (var i = 0; i < mutators.length; i++) {
            if (!mutators[i].isEqualTo(otherMutators[i])) return false;
        }
        return true;
    };

    /**
     * @returns {string}
     */
    this.toString = function () {
        return mutators.reduce(function (previous, current) {
            return previous + ',' + current;
        }).toString();
    };
}



/**
 * @param {String} identifier
 * @param {String} publicUrlPattern
 * @param {String} mutatedUrlPattern
 * @constructor
 */
function MutableImage(identifier, publicUrlPattern, mutatedUrlPattern) {
    var self = this;
    
    /**
     * @returns {String}
     */
    this.getOriginUrl = function () {
        return self._substituteInPattern('$identifier', identifier, publicUrlPattern);
    };

    /**
     * @returns {String}
     */
    this.getIdentifier = function () {
        return identifier;
    };

    /**
     * @param {Mutation} mutation
     * @return {String}
     */
    this.getMutatedUrl = function (mutation) {
        var fileName = identifier.replace(/^.*[\\\/]/, '');
        var pattern = mutatedUrlPattern;

        pattern = self._substituteInPattern('$originIdentifier', identifier, pattern);
        pattern = self._substituteInPattern('$mutation', self._stringifyMutation(mutation), pattern);
        pattern = self._substituteInPattern('$maxAge', null, pattern);
        return self._substituteInPattern('$targetFileName', fileName, pattern);
    };

    /**
     * @param {Mutation} mutation
     * @returns {string}
     * @private
     */
    this._stringifyMutation = function (mutation) {
        var string = mutation.toString();

        while (mutation.getParent() != null) {
            mutation = mutation.getParent();
            string = mutation + ',' + string;
        }

        return string;
    };

  /**
   * @param {String} search
   * @param {String} replace
   * @param {String} pattern
   * @return {String}
   * @private
   */
    this._substituteInPattern = function (search, replace, pattern) {
        var matches = pattern.match('\\' + search + '(\\((\\w{1,})\\)){0,1}');
        if (matches !== null) {
            if (replace === null && matches[2]) {
                replace = matches[2];
            }

            return pattern.replace(matches[0], replace);
        } else {
            throw Error('Cannot replace ['+search+'] by ['+replace+']! String ['+pattern+'] does not contain ['+search+']')
        }
    };
}



/**
 * @param {String} name
 * @param {String} value
 * @constructor
 */
function Mutator(name, value) {
    /**
     * @returns {String}
     */
    this.getName = function () {
        return name;
    };

    /**
     * @returns {String}
     */
    this.getValue = function () {
        return value;
    };

    /**
     * @param {Mutator} other
     * @returns {boolean}
     */
    this.isEqualTo = function (other) {
        return (other.getName() == name) && (other.getValue() == value);
    };

    /**
     * @returns {string}
     */
    this.toString = function () {
        return name + '_' + value;
    };
}



/**
 * @param {MutableImage} mutableImage
 * @param {Array.<Mutator>} shadowMutators  //TODO: RENAME !!!
 * @constructor
 */
function UrlProvider(mutableImage, shadowMutators) {
    var self = this;

    /**
     * @param {Mutation} mutation
     */
    this.createUrl = function (mutation) {
        mutation = self._tryApplyShadowMutators(mutation);

        if (mutation === null)
            return mutableImage.getOriginUrl();

        return mutableImage.getMutatedUrl(mutation);
    };

    /**
     * @param {Mutation} mutation
     * @returns {Mutation}
     * @private
     */
    this._tryApplyShadowMutators = function (mutation) {
        if (shadowMutators.length === 0)
            return mutation;

        return new Mutation(shadowMutators, mutation, Date.now());
    };
}



/**
 * @param {MutationsManager} mutationsManager
 * @param {function} onChange
 * @constructor
 */
function StateManager(mutationsManager, onChange) {
    var self = this;

    /** @type {Array.<Mutation>} */
    var previousMutations = [];

    /**
     * @returns {Mutation}
     */
    this.isUndoPossible = function () {
        return mutationsManager.getCurrentMutation();
    };

    /**
     * @returns {undefined}
     */
    this.undo = function () {
        if (self.isUndoPossible()) {
            var currentMutation = mutationsManager.getCurrentMutation();
            previousMutations.push(currentMutation);
            mutationsManager.setCurrentMutation(currentMutation.getParent());
            onChange();
        }
    };

    /**
     * @returns {boolean}
     */
    this.isRedoPossible = function () {
        return previousMutations.length !== 0;
    };

    /**
     * @returns {undefined}
     */
    this.redo = function () {
        if (self.isRedoPossible()) {
            var previousMutation = previousMutations.pop();
            mutationsManager.setCurrentMutation(previousMutation);
            onChange();
        }
    };

    /**
     * @param {Array.<Mutator>} mutators
     * @returns {Mutation}
     */
    this.mutate = function (mutators) {
        previousMutations = [];
        var mutation = mutationsManager.mutate(mutators);

        onChange();
        return mutation;
    };

    /**
     * @returns {Mutation}
     */
    this.getCurrentMutation = function () {
        return mutationsManager.getCurrentMutation();
    };

    /**
     * @returns {MutationsSnapshot}
     */
    this.createSnapshot = function () {
        var mutations = mutationsManager.getMutations();
        var currentMutation = mutationsManager.getCurrentMutation();
        return new MutationsSnapshot(mutations, currentMutation);
    };
}



/**
 * @param {Array.<Mutation>} mutations
 * @param {Mutation} currentMutation
 * @constructor
 */
function MutationsSnapshot(mutations, currentMutation) {
    var self = this;

    /**
     * @param {Array.<Mutation>} mutations
     * @param {String} position
     * @returns {Array}
     */
    var createMutationsSnapshot = function (mutations, position) {
        var snapshot = [];
        for (var i = 0; i < mutations.length; i++) {
            var mutation = mutations[i];
            snapshot.push(createMutationSnapshot(mutation, position + i));
        }
        return snapshot;
    };

    /**
     * @param {Mutation} mutation
     * @param {String} position
     * @return {Object}
     */
    var createMutationSnapshot = function (mutation, position) {
        if (mutation === currentMutation)
            self.currentMutation = position;

        return {
            timestamp: mutation.getTimestamp(),
            tags: mutation.getTags(),
            mutators: createMutatorsSnapshot(mutation.getMutators()),
            children: createMutationsSnapshot(mutation.getChildren(), position + ',')
        };
    };

    /**
     * @param {Array.<Mutator>} mutators
     * @return {Array}
     * @private
     */
    var createMutatorsSnapshot = function (mutators) {
        var snapshot = [];
        for (var j = 0; j < mutators.length; j++) {
            var mutator = mutators[j];
            snapshot.push({name: mutator.getName(), value: mutator.getValue()});
        }
        return snapshot;
    };

    /** @type {Array|null} */
    this.currentMutation = null;

    /** @type {Array} */
    this.mutations = createMutationsSnapshot(mutations, '');
}



/**
 * @param {MutationsSnapshot} snapshot
 * @constructor
 */
function ReconstructedMutationsSnapshot(snapshot) {
    var self = this;

    /**
     * @param {Array} mutationsSnapshot
     * @param {Mutation} parent
     * @param {String} position
     * @return {Array.<Mutation>}
     */
    var reconstructMutationsSnapshot = function (mutationsSnapshot, parent, position) {
        var mutations = [];
        for (var i = 0; i < mutationsSnapshot.length; i++) {
            mutations.push(reconstructMutationSnapshot(mutationsSnapshot[i], parent, position + i));
        }
        return mutations;
    };

    /**
     * @param {Array} mutationSnapshot
     * @param {Mutation} parent
     * @param {String} position
     * @return {Mutation}
     */
    var reconstructMutationSnapshot = function (mutationSnapshot, parent, position) {
        var mutators = reconstructMutatorsSnapshot(mutationSnapshot['mutators']);
        var mutation = new Mutation(mutators, parent, mutationSnapshot['timestamp']);
        mutation._setTags(mutationSnapshot['tags']);

        var children = reconstructMutationsSnapshot(mutationSnapshot['children'], mutation, position + ',');
        mutation._setChildren(children);

        if (position === snapshot.currentMutation)
            self.currentMutation = mutation;

        return mutation;
    };

    /**
     * @param {Array} mutatorsSnapshot
     * @return {Array.<Mutator>}
     */
    var reconstructMutatorsSnapshot = function (mutatorsSnapshot) {
        var mutators = [];
        for (var i = 0; i < mutatorsSnapshot.length; i++) {
            var mutatorSnapshot = mutatorsSnapshot[i];
            mutators.push(new Mutator(mutatorSnapshot.name, mutatorSnapshot.value));
        }
        return mutators;
    };

    /** @type {Mutation|null} */
    this.currentMutation = null;

    /** @type {Array.<Mutation>} */
    this.mutations = reconstructMutationsSnapshot(snapshot.mutations, null, '');
}



function EverlutionCropperOptions() {
    /** @type {boolean} */
    this.zoomable = false;

    /** @type {number|NaN} */
    this.aspectRatio = NaN;

    /** @type {boolean} */
    this.viewDownscaling = true;

    /** @type {MutationsSnapshot|null} */
    this.stateSnapshot = null;
}



/**
 * @param {HTMLImageElement} element
 * @param {MutableImage} mutableImage
 * @param {EverlutionCropperOptions} options
 * @constructor
 */
function EverlutionCropper(element, mutableImage, options) {
    /** @type {MutationsManager} */
    var mutationsManager;

    /** @type {StateManager} */
    var stateManager;

    var refreshImage = function () {
        var url = urlProvider.createUrl(mutationsManager.getCurrentMutation());
        cropper.replace(url);
    };

    if (!options.stateSnapshot) {
        mutationsManager = new MutationsManager([], null);
        stateManager = new StateManager(mutationsManager, refreshImage);
    } else {
        var reconstructedData = new ReconstructedMutationsSnapshot(options.stateSnapshot);
        mutationsManager = new MutationsManager(reconstructedData.mutations, reconstructedData.currentMutation);
        stateManager = new StateManager(mutationsManager, refreshImage);
    }

    /**
     * @returns {UrlProvider}
     */
    var createUrlProvider = window.onresize = function () {
        var shadowMutators = [];
        if (options.viewDownscaling) {
            var width = element.parentNode.offsetWidth;
            var height = element.parentNode.offsetHeight;
            shadowMutators = [new Mutator('th', width + 'x' + height + '>')];
        }
        return new UrlProvider(mutableImage, shadowMutators);
    };

    /** @type {UrlProvider} */
    var urlProvider = createUrlProvider();

    element.src = urlProvider.createUrl(mutationsManager.getCurrentMutation());

    var cropper = new Cropper(element, {
        checkCrossOrigin: false,
        zoomable: options.zoomable,
        aspectRatio: options.aspectRatio,
        responsive: true,
        viewMode: 2
    });
    element.onload = cropper.clear;

    this.crop = function () {
        var cropData = cropper.getData(true);

        var width = cropData.width;
        var height = cropData.height;
        var x = cropData.x;
        var y = cropData.y;

        var mutator = new Mutator('c', width + 'x' + height + '+' + x + '+' + y);
        stateManager.mutate([mutator]);
    };

    this.relativeCrop = function () {
        var cropData = cropper.getData(true);
        var canvasData = cropper.getCanvasData();

        var width = (100 / canvasData.naturalWidth * cropData.width).toFixed(4);
        var height = (100 / canvasData.naturalHeight * cropData.height).toFixed(4);
        var x = (100 / canvasData.naturalWidth * cropData.x).toFixed(4);
        var y = (100 / canvasData.naturalHeight * cropData.y).toFixed(4);

        var mutator = new Mutator('rc', width + 'x' + height + '+' + x + '+' + y);
        stateManager.mutate([mutator]);
    };

    /**
     * @returns {StateManager}
     */
    this.getState = function () {
        return stateManager;
    };
}
