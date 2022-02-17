import renderer from '../utils/renderer';

module.exports = (app, store, template) => {
  app.get('/(*)/ranking/(*)/(*)', (req,res) => {
    res.render(template, renderer(req, store, 'rankingPage'));
  });
};
