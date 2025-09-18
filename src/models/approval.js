'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Approval extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      Approval.belongsTo(models.User, { as: 'Lecturer', foreignKey: 'lecturerId' });
      Approval.belongsTo(models.ThesisVersion, { foreignKey: 'thesisVersionId' });
    }
  }
  Approval.init({
    thesisVersionId: DataTypes.INTEGER,
    lecturerId: DataTypes.INTEGER,
    signature: DataTypes.TEXT
  }, {
    sequelize,
    modelName: 'Approval',
  });
  return Approval;
};