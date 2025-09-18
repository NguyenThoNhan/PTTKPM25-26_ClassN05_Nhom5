'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class ThesisVersion extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
      ThesisVersion.belongsTo(models.Thesis, { foreignKey: 'thesisId' });

      ThesisVersion.hasOne(models.Approval, { foreignKey: 'thesisVersionId' }); // Quan hệ mới

    }
  }
  ThesisVersion.init({
    thesisId: DataTypes.INTEGER,
    filePath: DataTypes.STRING,
    versionName: DataTypes.STRING,
    fileHash: DataTypes.STRING,
    submissionDate: DataTypes.DATE
  }, {
    sequelize,
    modelName: 'ThesisVersion',
  });
  return ThesisVersion;
};